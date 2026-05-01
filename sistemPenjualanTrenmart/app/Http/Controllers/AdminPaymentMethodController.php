<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class AdminPaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::orderBy('created_at','desc')->get();
        return view('admin.payment_methods.index', compact('methods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'account_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        PaymentMethod::create([
            'name' => $request->name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return back()->with('success','Metode pembayaran tersimpan');
    }

    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->update($request->only(['name','account_name','account_number','is_active']));
        return back()->with('success','Perubahan disimpan');
    }

    public function destroy($id)
    {
        PaymentMethod::findOrFail($id)->delete();
        return back()->with('success','Metode dihapus');
    }
}
