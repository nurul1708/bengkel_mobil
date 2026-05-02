<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/admin/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles, true)) {
            $redirectTo = match ($user->role) {
                'admin', 'owner' => '/admin/dashboard',
                'kasir' => '/admin/transaksi',
                'mekanik' => '/admin/booking',
                default => '/admin/login',
            };

            return redirect($redirectTo)->with('error', 'Anda tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}
