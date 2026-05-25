<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ShippingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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