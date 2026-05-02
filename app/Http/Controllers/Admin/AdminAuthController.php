<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function login()
    {
        return view('admin.auth.login');
    }

    public function authenticate(Request $request)
  {
    // Hilangkan secret_code dari validasi
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Hilangkan pengecekan secret_code ADMIN123

    if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {

        $user = Auth::user();

        // hanya role backend
        if (!in_array($user->role, ['admin','owner','kasir','mekanik'])) {
            Auth::logout();
            return back()->with('error', 'Akses ditolak!');
        }

            return match ($user->role) {
                'admin', 'owner' => redirect('/admin/dashboard'),
                'kasir' => redirect('/admin/transaksi'),
                'mekanik' => redirect('/admin/booking'),
                default => redirect('/admin/login'),
            };
        }

    return back()->with('error', 'Email atau password salah!');
}
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/admin/login');
    }
}
