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

        // Pastikan fungsi isAdmin() ada di Model User
        if ($user->isAdmin()) {
            return $next($request);
        }
    }

    // Jika bukan admin, lempar ke beranda dengan pesan error
    return redirect('/')->with('error', 'Akses ditolak! Anda bukan Admin.');
    }   
}