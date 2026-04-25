<?php

namespace App\Http\Controllers;

use App\Models\BerandaSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class TentangController extends Controller
{
    public function index(Request $request)
    {
        $defaults = [
            'tentang_banner' => null,
            'tentang_nama_toko' => 'TRENMART',
            'tentang_tagline' => 'Toko Alat Tulis Lengkap & Terpercaya',
            'tentang_deskripsi' => 'TRENMART adalah toko alat tulis kantor yang melayani kebutuhan pelajar, kantor, dan UMKM dengan koleksi lengkap.',
            'tentang_alamat' => 'Jl. Pasar Baru No. 123, Indonesia',
            'tentang_maps_link' => '',
            'tentang_telepon' => '+62 812-3456-7890',
            'tentang_email' => 'halo@trenmart.id',
            'tentang_jam_operasional' => 'Senin - Sabtu, 08.00 - 20.00',
            'tentang_fitur' => json_encode([
                ['icon' => 'shop', 'title' => 'Grosir & Eceran', 'description' => 'Melayani pembelian dalam jumlah kecil maupun besar dengan harga bersaing.'],
                ['icon' => 'truck', 'title' => 'Pengiriman Cepat', 'description' => 'Pesanan dikirim setiap hari kerja langsung dari toko kami.'],
                ['icon' => 'patch-check', 'title' => 'Produk Berkualitas', 'description' => 'Hanya menjual merek terpercaya untuk kebutuhan kantor dan sekolah.'],
                ['icon' => 'headset', 'title' => 'Pelayanan Ramah', 'description' => 'Tim kami siap membantu menjawab pertanyaan Anda sebelum membeli.'],
            ], JSON_UNESCAPED_UNICODE),
        ];

        $settings = BerandaSetting::whereIn('key', array_keys($defaults))->pluck('value', 'key')->toArray();
        $data = array_merge($defaults, $settings);

        $fiturUnggulan = json_decode($data['tentang_fitur'] ?? '[]', true);
        if (!is_array($fiturUnggulan) || count($fiturUnggulan) === 0) {
            $fiturUnggulan = json_decode($defaults['tentang_fitur'], true);
        }

        $isAdminEditMode = Auth::check() && Auth::user()->role === 'admin' && !$request->boolean('preview');

        return view('tentang-kami', [
            'data' => $data,
            'fiturUnggulan' => $fiturUnggulan,
            'mapEmbedUrl' => $this->buildMapEmbedUrl($data['tentang_maps_link'] ?? '', $data['tentang_alamat'] ?? ''),
            'isAdminEditMode' => $isAdminEditMode,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'tentang_banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'tentang_nama_toko' => 'required|string|max:255',
            'tentang_tagline' => 'nullable|string|max:255',
            'tentang_deskripsi' => 'nullable|string|max:3000',
            'tentang_alamat' => 'nullable|string|max:500',
            'tentang_maps_link' => 'nullable|string|max:500',
            'tentang_telepon' => 'nullable|string|max:50',
            'tentang_email' => 'nullable|email|max:255',
            'tentang_jam_operasional' => 'nullable|string|max:255',
            'feature_icon' => 'nullable|array',
            'feature_icon.*' => 'nullable|string|max:50',
            'feature_title' => 'nullable|array',
            'feature_title.*' => 'nullable|string|max:255',
            'feature_description' => 'nullable|array',
            'feature_description.*' => 'nullable|string|max:1000',
        ]);

        $bannerName = BerandaSetting::where('key', 'tentang_banner')->value('value');
        if ($request->hasFile('tentang_banner')) {
            if ($bannerName && File::exists(public_path('storage/' . $bannerName))) {
                File::delete(public_path('storage/' . $bannerName));
            }

            $bannerName = time() . '_' . uniqid() . '.' . $request->file('tentang_banner')->extension();
            $request->file('tentang_banner')->move(public_path('storage'), $bannerName);
        }

        $fitur = [];
        $icons = $request->input('feature_icon', []);
        $titles = $request->input('feature_title', []);
        $descriptions = $request->input('feature_description', []);

        $max = max(count($icons), count($titles), count($descriptions));
        for ($i = 0; $i < $max; $i++) {
            $title = trim((string)($titles[$i] ?? ''));
            $description = trim((string)($descriptions[$i] ?? ''));
            $icon = trim((string)($icons[$i] ?? 'shop'));

            if ($title === '' && $description === '') {
                continue;
            }

            $fitur[] = [
                'icon' => $icon !== '' ? $icon : 'shop',
                'title' => $title,
                'description' => $description,
            ];
        }

        if (count($fitur) === 0) {
            $fitur[] = [
                'icon' => 'shop',
                'title' => 'Fitur Baru',
                'description' => 'Tambahkan deskripsi fitur di sini.',
            ];
        }

        $payload = [
            'tentang_banner' => $bannerName,
            'tentang_nama_toko' => $request->tentang_nama_toko,
            'tentang_tagline' => $request->tentang_tagline,
            'tentang_deskripsi' => $request->tentang_deskripsi,
            'tentang_alamat' => $request->tentang_alamat,
            'tentang_maps_link' => $request->tentang_maps_link,
            'tentang_telepon' => $request->tentang_telepon,
            'tentang_email' => $request->tentang_email,
            'tentang_jam_operasional' => $request->tentang_jam_operasional,
            'tentang_fitur' => json_encode($fitur, JSON_UNESCAPED_UNICODE),
        ];

        foreach ($payload as $key => $value) {
            BerandaSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('tentang')->with('success', 'Informasi halaman Tentang Kami berhasil diperbarui.');
    }

    private function buildMapEmbedUrl(string $mapsLink, string $address): string
    {
        if (str_contains($mapsLink, '/maps/embed')) {
            return $mapsLink;
        }

        if ($mapsLink !== '') {
            return 'https://www.google.com/maps?q=' . urlencode($mapsLink) . '&output=embed';
        }

        if ($address !== '') {
            return 'https://www.google.com/maps?q=' . urlencode($address) . '&output=embed';
        }

        return 'https://www.google.com/maps?q=' . urlencode('Indonesia') . '&output=embed';
    }
}
