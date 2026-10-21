<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Kasir
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user login DAN rolenya 'cashier'
        if (Auth::check() && Auth::user()->role == 'cashier') {
            return $next($request);
        }

        // Jika BUKAN cashier (atau belum login), tampilkan Akses Ditolak (403)
        abort(403, 'Akses Ditolak!');
    }
}