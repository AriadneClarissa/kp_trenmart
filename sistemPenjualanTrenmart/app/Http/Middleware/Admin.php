<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini jika belum ada
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    // File: app/Http/Middleware/Admin.php

public function handle(Request $request, Closure $next): Response
{
    // Cek apakah user sudah login
    if (Auth::check()) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Izinkan owner, admin, dan kasir masuk ke area panel
        if ($user->isAdmin() || (method_exists($user, 'isCashier') && $user->isCashier())) {
            return $next($request);
        }
    }

    // Jika bukan admin/kasir, lempar ke beranda dengan pesan error
    return redirect('/')->with('error', 'Akses ditolak!');
    }   
}