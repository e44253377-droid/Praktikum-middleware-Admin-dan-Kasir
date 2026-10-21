<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user login DAN rolenya 'admin'
        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request); // Jika admin, izinkan masuk
        }

        // Jika BUKAN admin (atau belum login), tampilkan Akses Ditolak (403)[cite: 3]
        abort(403, 'Akses Ditolak!');
    }
}