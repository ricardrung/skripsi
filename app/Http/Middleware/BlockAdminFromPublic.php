<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Symfony\Component\HttpFoundation\Response;

class BlockAdminFromPublic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (Auth::check() && Auth::user()->role === 'admin') {
    //         abort(403, 'Admin tidak diperbolehkan mengakses halaman ini.');
    //     }

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next): Response
{
    if (Auth::check() && Auth::user()->role === 'admin') {
        if ($request->isMethod('get')) {
            // Jika hanya GET, izinkan
            return $next($request);
        } else {
            // Jika POST, PUT, DELETE, block
            abort(403, 'Admin tidak diperbolehkan melakukan aksi di halaman ini.');
        }
    }

    return $next($request);
}

}
