<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Keranjang;
use App\Models\BerandaSetting;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        $customerAddress = Auth::user()->home_address;
        $shippingPreview = $this->calculateShipping($storeAddress, $customerAddress);

        // 4. Kirim semua variabel ke view select_payment
        return view('checkout.select_payment', compact('cartItems', 'paymentMethods', 'total', 'storeAddress', 'customerAddress', 'shippingPreview'));
    }

    public function addressSuggestions(Request $request)
    {
        $data = $request->validate([
            'q' => 'required|string|min:3|max:255',
        ]);

        $headers = [
            'User-Agent' => 'TrenmartAddressAutocomplete/1.0',
            'Accept-Language' => 'id',
        ];

        $response = Http::withHeaders($headers)->get('https://nominatim.openstreetmap.org/search', [
            'q' => $data['q'],
            'format' => 'jsonv2',
            'addressdetails' => 1,
            'limit' => 5,
            'countrycodes' => 'id',
        ]);

        $rows = $response->successful() ? $response->json() : [];

        // Fallback query tanpa countrycodes jika hasil awal kosong.
        if (empty($rows)) {
            $fallbackResponse = Http::withHeaders($headers)->get('https://nominatim.openstreetmap.org/search', [
                'q' => $data['q'],
                'format' => 'jsonv2',
                'addressdetails' => 1,
                'limit' => 5,
            ]);
            $rows = $fallbackResponse->successful() ? $fallbackResponse->json() : [];
        }

        $payload = collect($rows)
            ->map(function ($row) {
                return [
                    'label' => $row['display_name'] ?? '',
                    'lat' => $row['lat'] ?? null,
                    'lon' => $row['lon'] ?? null,
                ];
            })
            ->filter(fn($item) => !empty($item['label']))
            ->values();

        return response()->json([
            'success' => true,
            'suggestions' => $payload,
        ]);
    }

    public function shippingQuote(Request $request)
    {
        $data = $request->validate([
            'pickup_method' => 'required|in:delivery,pickup',
            'shipping_address' => 'nullable|string|max:500',
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

        $quote = $this->calculateShipping($this->getStoreAddress(), $data['shipping_address']);

        return response()->json([
            'success' => true,
            'distance_km' => $quote['distance_km'],
            'shipping_cost' => $quote['shipping_cost'],
        ]);
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'pickup_method' => 'required|in:delivery,pickup',
            'shipping_address' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $cartItems = Keranjang::with(['produk', 'bundling'])->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $harga = $item->bundling_id
                ? $item->bundling->bundling_price
                : ($item->harga_at_time ?? $item->produk->harga_jual_umum);
            $subtotal += $harga * $item->jumlah;
        }

        $shippingAddress = trim((string) ($data['shipping_address'] ?? ''));
        $shippingDistanceKm = 0;
        $shippingCost = 0;

        if ($data['pickup_method'] === 'delivery') {
            if ($shippingAddress === '') {
                return back()->withErrors(['shipping_address' => 'Alamat pengiriman wajib diisi untuk delivery.'])->withInput();
            }

            $quote = $this->calculateShipping($this->getStoreAddress(), $shippingAddress);
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

        // Jangan hapus keranjang di sini - hapus setelah payment proof diverifikasi
        // Ini agar user bisa kembali dan keranjang masih tersedia

        return redirect()->route('checkout.upload_proof', $order->id)->with('success', 'Pesanan berhasil dibuat. Silakan unggah bukti pembayaran.');
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
            
            $order->update([
                'bukti_pembayaran' => $path,
                'status' => 'menunggu_verifikasi',
                'payment_status' => 'waiting_confirmation',
            ]);

            // BARU HAPUS KERANJANG setelah payment proof sukses diunggah
            Keranjang::where('user_id', $user->id)->delete();

            return redirect()->route('checkout.waiting', $order->id)
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

    private function getStoreAddress(): string
    {
        return BerandaSetting::where('key', 'tentang_alamat')->value('value')
            ?: 'Jl. Pasar Baru No. 123, Indonesia';
    }

    private function calculateShipping(string $storeAddress, string $customerAddress): array
    {
        $storeCoordinates = $this->geocodeAddress($storeAddress);
        $customerCoordinates = $this->geocodeAddress($customerAddress);

        if (!$storeCoordinates || !$customerCoordinates) {
            return [
                'distance_km' => null,
                'shipping_cost' => 15000,
            ];
        }

        $distanceKm = $this->haversineDistance(
            (float) $storeCoordinates['lat'],
            (float) $storeCoordinates['lon'],
            (float) $customerCoordinates['lat'],
            (float) $customerCoordinates['lon']
        );

        $shippingCost = $distanceKm < 5
            ? 0
            : max(0, (int) floor($distanceKm / 5) * 5000);

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