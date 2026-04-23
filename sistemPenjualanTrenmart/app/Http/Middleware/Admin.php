<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini jika belum ada
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if (Auth::check() && $user->isAdmin()) {
        return $next($request);
    }

    return redirect('/')->with('error', 'Akses ditolak!');
    }
}