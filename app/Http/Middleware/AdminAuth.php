<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/admin/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Check if user has backend role
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'owner', 'kasir', 'mekanik'])) {
            Auth::logout();
            return redirect('/admin/login')->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}
