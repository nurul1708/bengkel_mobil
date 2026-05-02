<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ClientAuthController extends Controller
{
       public function loginForm()
    {
        return view('client.login');
    }
    public function login(Request $request)
{
    $user = User::where('email', $request->email)
        ->where('role', 'customer')
        ->first();

    if (!$user) {
        return redirect()->route('client.loginForm')
            ->with(['email' => 'Email atau password salah']);
    }

    if (!$user->is_verified) {
        return redirect()->route('client.loginForm')
            ->with(['email' => 'Akun Anda belum verifikasi. Silakan cek email untuk kode OTP.']);
    }

    if (auth()->guard('client')->attempt([
        'email' => $request->email,
        'password' => $request->password,
        'role' => 'customer'
    ])) {
        return redirect()->route('customer.home');
    }

    return redirect()->route('client.loginForm')
        ->with(['email' => 'Email atau password salah']);
}
    public function logout()
    {
        
        if(auth()->guard('client')->check()) {
            auth()->guard('client')->logout();
            return redirect()->route('customer.home');
        }
        if(auth()->guard('admin')->check()) {
            auth()->guard('admin')->logout();
            return redirect()->route('admin.login');
        }
    }
    public function registerForm()
{
    return view('client.register');
}

public function register(Request $request)
{
    // VALIDASI YANG BENAR
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'phone' => 'required',
        'address' => 'required',
    ]);

    // HANDLE FOTO
    $fotoPath = null;
    if ($request->hasFile('foto')) {
        $fotoPath = $request->file('foto')->store('profiles', 'public');
    }

    // SIMPAN KE DATABASE
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => 'customer',
        'phone' => $request->no_hp,
        'address' => $request->alamat,
        'photo' => $fotoPath
    ]);

    return redirect()->route('client.loginForm')
        ->with('success', 'Berhasil daftar!');

}
}
