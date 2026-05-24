<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ShippingSetting;
use Illuminate\Http\Request;

class AdminShippingSettingController extends Controller
{
    public function edit()
    {
        $settings = ShippingSetting::first() ?? new ShippingSetting();
        return view('admin.shipping.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'free_limit' => 'required|numeric',
            'price_per_km' => 'required|integer',
        ]);

        // Mengupdate data yang sudah ada, atau membuat baru jika belum ada
        $settings = ShippingSetting::first();
        
        if ($settings) {
            $settings->update($request->all());
        } else {
            ShippingSetting::create($request->all());
        }

        return back()->with('success', 'Pengaturan ongkir berhasil diperbarui!');
    }
}