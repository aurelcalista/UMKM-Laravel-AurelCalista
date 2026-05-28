<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Cek apakah role user sesuai dengan yang diizinkan
        // Parameter $roles bisa berisi: 'admin', 'user', dll
        if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        // Jika role tidak sesuai
        abort(403, 'Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}