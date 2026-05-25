<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Keranjang;
use App\Models\BerandaSetting;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        
        // 1. Ambil item keranjang milik user
        $cartItems = Keranjang::where('user_id', $user_id)->get();

        // Jika keranjang kosong, balikkan ke halaman keranjang
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // 2. Hitung Total (Logic sesuai diskusi kita sebelumnya)
        $total = 0;
        foreach ($cartItems as $item) {
            $harga = $item->bundling_id 
                     ? $item->bundling->bundling_price 
                     : ($item->harga_at_time ?? $item->produk->harga_jual_umum);
            $total += $harga * $item->jumlah;
        }

        // 3. Ambil Metode Pembayaran
        $paymentMethods = PaymentMethod::all();

        $storeAddress = $this->getStoreAddress();
        $customerAddress = (string) (Auth::user()->home_address ?? '');
        $shippingPreview = $this->calculateShipping($storeAddress, $customerAddress);

        // 4. Kirim semua variabel ke view select_payment
        return view('checkout.select_payment', compact('cartItems', 'paymentMethods', 'total', 'storeAddress', 'customerAddress', 'shippingPreview'));
    }

    // Di CheckoutController.php
    public function addressSuggestions(Request $request)
    {
        $query = $request->query('q');
        if (! $query) {
            return response()->json(['success' => false, 'suggestions' => []]);
        }

        $normalizedQuery = trim($query);
        // Jika pengguna tidak menyebutkan Palembang, tambahkan agar pencarian dibatasi ke Kota Palembang
        if (! str_contains(strtolower($normalizedQuery), 'palembang')) {
            $normalizedQuery .= ' Palembang';
        }

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'TrenmartShippingCalculator/1.0',
                'Accept-Language' => 'id',
            ])->timeout(10)->get('https://nominatim.openstreetmap.org/search', [
                'q' => $normalizedQuery,
                'format' => 'jsonv2',
                'limit' => 6,
                'countrycodes' => 'id',
                // Bounding box Kota Palembang: left_lon, top_lat, right_lon, bottom_lat
                'viewbox' => '104.6,-2.8,105.0,-3.2',
                'bounded' => 1,
                'addressdetails' => 1,
            ]);

            if (! $response->successful()) {
                return response()->json([
                    'success' => false,
                    'suggestions' => [],
                ]);
            }

            $suggestions = collect($response->json() ?? [])
                ->filter(function ($item) {
                    $display = strtolower((string) ($item['display_name'] ?? ''));
                    $city = strtolower((string) ($item['address']['city'] ?? $item['address']['town'] ?? $item['address']['county'] ?? ''));
                    return str_contains($display, 'palembang') || str_contains($city, 'palembang');
                })
                ->values()
                ->all();

            return response()->json([
                'success' => true,
                'suggestions' => $suggestions,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Address suggestion failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'suggestions' => [],
            ]);
        }
    }

    public function reverseGeocode(Request $request)
    {
        $data = $request->validate([
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'TrenmartShippingCalculator/1.0',
                'Accept-Language' => 'id',
            ])->timeout(10)->get('https://nominatim.openstreetmap.org/reverse', [
                'format' => 'jsonv2',
                'lat' => (float) $data['lat'],
                'lon' => (float) $data['lon'],
                'zoom' => 18,
                'addressdetails' => 1,
            ]);

            if (! $response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal reverse geocode.',
                ], 422);
            }

            $payload = $response->json() ?? [];
            $address = (string) ($payload['display_name'] ?? '');
            $city = strtolower((string) ($payload['address']['city'] ?? $payload['address']['town'] ?? $payload['address']['county'] ?? ''));
            $isPalembang = str_contains(strtolower($address), 'palembang') || str_contains($city, 'palembang');

            if (! $isPalembang || $address === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Lokasi di luar jangkauan Palembang.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'address' => $address,
                'lat' => (float) $data['lat'],
                'lon' => (float) $data['lon'],
            ]);
        } catch (\Throwable $e) {
            Log::warning('Reverse geocode failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal reverse geocode.',
            ], 422);
        }
    }

    public function shippingQuote(Request $request)
    {
        $data = $request->validate([
            'pickup_method' => 'required|in:delivery,pickup',
            'shipping_address' => 'nullable|string|max:500',
            'shipping_lat' => 'nullable|numeric',
            'shipping_lon' => 'nullable|numeric',
        ]);

        if ($data['pickup_method'] === 'pickup') {
            return response()->json([
                'success' => true,
                'distance_km' => 0,
                'shipping_cost' => 0,
            ]);
        }

        if (empty($data['shipping_address'])) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat pengiriman wajib diisi untuk delivery.',
            ], 422);
        }

        $quote = $this->calculateShipping(
            $this->getStoreAddress(),
            $data['shipping_address'],
            isset($data['shipping_lat'], $data['shipping_lon']) ? [
                'lat' => (float) $data['shipping_lat'],
                'lon' => (float) $data['shipping_lon'],
            ] : null
        );

        return response()->json([
            'success' => true,
            'distance_km' => $quote['distance_km'],
            'shipping_cost' => $quote['shipping_cost'],
        ]);
    }

    public function placeOrder(Request $request)
    {
        try {
            $data = $request->validate([
                'payment_method_id' => 'required|exists:payment_methods,id',
                'pickup_method' => 'required|in:delivery,pickup',
                'shipping_address' => 'nullable|string|max:500',
                'shipping_lat' => 'nullable|numeric',
                'shipping_lon' => 'nullable|numeric',
            ]);

            // --- VALIDASI WILAYAH (Hanya untuk delivery) ---
            if ($data['pickup_method'] === 'delivery') {
                $addr = strtolower($data['shipping_address'] ?? '');
                $isValid = str_contains($addr, 'sumatera selatan') || 
                           str_contains($addr, 'sumsel') || 
                           str_contains($addr, 'palembang');
                
                if (!$isValid) {
                    return back()->withInput()->with('error', 'Maaf, pengiriman saat ini hanya tersedia di wilayah Sumatera Selatan.');
                }
            }
            // ----------------------------------------------

            $user = Auth::user();
            $cartItems = Keranjang::with(['produk', 'bundling'])->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
            }

            // Hitung Subtotal
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $harga = $item->bundling_id
                    ? $item->bundling->bundling_price
                    : ($item->harga_at_time ?? $item->produk->harga_jual_umum);
                $subtotal += $harga * $item->jumlah;
            }

            $shippingAddress = trim((string) ($data['shipping_address'] ?? ''));
            $shippingCoordinates = isset($data['shipping_lat'], $data['shipping_lon'])
                ? ['lat' => (float) $data['shipping_lat'], 'lon' => (float) $data['shipping_lon']]
                : null;
            
            $shippingDistanceKm = 0;
            $shippingCost = 0;

            $order = DB::transaction(function () use ($data, $user, $cartItems, $subtotal, $shippingAddress, $shippingCoordinates, &$shippingDistanceKm, &$shippingCost) {
                if ($data['pickup_method'] === 'delivery') {
                    if ($shippingAddress === '') {
                        throw new \RuntimeException('Alamat pengiriman wajib diisi untuk delivery.');
                    }

                    $quote = $this->calculateShipping($this->getStoreAddress(), $shippingAddress, $shippingCoordinates);
                    $shippingDistanceKm = $quote['distance_km'];
                    $shippingCost = $quote['shipping_cost'];
                }

                $order = Order::create([
                    'order_number' => 'TRM-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                    'user_id' => $user->id,
                    'total' => $subtotal + $shippingCost,
                    'payment_method_id' => $data['payment_method_id'],
                    'pickup_method' => $data['pickup_method'],
                    'shipping_address' => $data['pickup_method'] === 'delivery' ? $shippingAddress : null,
                    'shipping_distance_km' => $data['pickup_method'] === 'delivery' ? $shippingDistanceKm : null,
                    'shipping_cost' => $shippingCost,
                    'payment_status' => 'pending',
                    'order_status' => 'new',
                ]);

                foreach ($cartItems as $item) {
                    $harga = $item->bundling_id
                        ? $item->bundling->bundling_price
                        : ($item->harga_at_time ?? $item->produk->harga_jual_umum);

                    $order->items()->create([
                        'kd_produk' => $item->kd_produk,
                        'quantity' => $item->jumlah,
                        'price' => $harga,
                    ]);
                }

                $order->load('items.produk');
                $order->deductStockForCompletedOrder();

                return $order;
            });

            return redirect()->route('checkout.upload_proof', $order->id)->with('success', 'Pesanan berhasil dibuat. Silakan unggah bukti pembayaran.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', $e->getMessage() ?: 'Gagal membuat pesanan.');
        }
    }

    // HALAMAN UPLOAD BUKTI (Setelah buat order)
  
    public function uploadProof($orderId)
    {
        $order = Order::with(['paymentMethod', 'items'])->findOrFail($orderId);
        return view('checkout.upload_proof', compact('order'));
    }

    // PROSES SIMPAN BUKTI TRANSFER
    public function storeProof(Request $request, $orderId)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::findOrFail($orderId);
        $user = Auth::user();

        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('bukti_transfer', 'public');
            
            // Save to the `payment_proof` column which the Order model expects
            $order->update([
                'payment_proof' => $path,
                'status' => 'menunggu_verifikasi',
                'payment_status' => 'waiting_confirmation',
            ]);

            // BARU HAPUS KERANJANG setelah payment proof sukses diunggah
            Keranjang::where('user_id', $user->id)->delete();

            return redirect()->route('pesanan.show', $order->id)
                             ->with('success', 'Bukti transfer berhasil diunggah!');
        }

        return back()->with('error', 'Gagal mengunggah foto.');
    }

    // HALAMAN TUNGGU VERIFIKASI
    
    public function waiting($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('checkout.waiting', compact('order'));
    }

    public function getStoreAddress(): string
    {
        // Tetapkan alamat toko Trenmart secara eksplisit agar perhitungan jarak konsisten.
        // Jika Anda ingin mengubahnya lewat dashboard, ganti return ini menjadi nilai dari BerandaSetting.
        return 'Jl. Jenderal Ahmad Yani, Tangga Takat, Kec. Seberang Ulu II, Kota Palembang, Sumatera Selatan 30265';
    }

    public function calculateShipping(string $storeAddress, string $customerAddress, ?array $customerCoordinates = null): array
    {
        // 1. Ambil setting dari tabel shipping_settings
        $settings = \App\Models\ShippingSetting::first() ?? new \App\Models\ShippingSetting([
            'free_limit' => 1.0,
            'price_per_km' => 2000,
        ]);

        $freeLimit = (float) $settings->free_limit;
        $pricePerKm = (int) ($settings->price_per_km ?? 0);

        $storeCoordinates = $this->geocodeAddress($storeAddress);
        $customerCoordinates = $customerCoordinates ?: $this->geocodeAddress($customerAddress);

        if (!$storeCoordinates || !$customerCoordinates) {
            return ['distance_km' => null, 'shipping_cost' => 15000];
        }

        $distanceKm = $this->haversineDistance(
            (float) $storeCoordinates['lat'],
            (float) $storeCoordinates['lon'],
            (float) $customerCoordinates['lat'],
            (float) $customerCoordinates['lon']
        );

        // 2. Logika perhitungan dinamis
        $shippingCost = 0;
        if ($distanceKm > $freeLimit) {
            // Contoh: Jika jarak 3km, free_limit 1km, maka (3 - 1) = 2km berbayar
            $shippingCost = (int) floor($distanceKm - $freeLimit) * $pricePerKm;
        }

        return [
            'distance_km' => round($distanceKm, 2),
            'shipping_cost' => $shippingCost,
        ];
    }

    private function geocodeAddress(string $address): ?array
    {
        $address = trim($address);
        if ($address === '') {
            return null;
        }

        $response = Http::withHeaders([
            'User-Agent' => 'TrenmartShippingCalculator/1.0',
            'Accept-Language' => 'id',
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $address,
            'format' => 'jsonv2',
            'limit' => 1,
            'countrycodes' => 'id',
            // Bounding box Kota Palembang: left_lon, top_lat, right_lon, bottom_lat
            'viewbox' => '104.6,-2.8,105.0,-3.2',
            'bounded' => 1,
        ]);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();
        if (empty($data[0]['lat']) || empty($data[0]['lon'])) {
            return null;
        }

        return [
            'lat' => $data[0]['lat'],
            'lon' => $data[0]['lon'],
        ];
    }

    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lonDelta / 2) ** 2;

        return 2 * $earthRadius * asin(min(1, sqrt($a)));
    }
    
}