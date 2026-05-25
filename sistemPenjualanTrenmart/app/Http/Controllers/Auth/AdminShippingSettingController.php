<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ShippingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AdminShippingSettingController extends Controller
{
    public function edit()
    {
        abort_unless(Auth::check() && Auth::user()->role === 'admin', 403);

        $settings = ShippingSetting::first() ?? new ShippingSetting();
        return view('admin.shipping.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->role === 'admin', 403);

        $request->validate([
            'flat_rate' => 'required|integer|min:0',
        ]);

        $payload = [];

        if (Schema::hasColumn('shipping_settings', 'flat_rate')) {
            $payload['flat_rate'] = (int) $request->flat_rate;
        } elseif (Schema::hasColumn('shipping_settings', 'price_per_km')) {
            $payload['price_per_km'] = (int) $request->flat_rate;
            if (Schema::hasColumn('shipping_settings', 'free_limit')) {
                $payload['free_limit'] = 0;
            }
        }

        if ($payload === []) {
            return back()->with('error', 'Skema tabel ongkir belum siap. Jalankan migrasi terlebih dahulu.');
        }

        $settings = ShippingSetting::first();

        if ($settings) {
            $settings->update($payload);
        } else {
            ShippingSetting::create($payload);
        }

        return back()->with('success', 'Pengaturan ongkir berhasil diperbarui!');
    }
}