<?php

namespace App\Http\Controllers;

use App\Mail\ClientOtpMail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class RegisterController extends Controller
{
    private const OTP_EXPIRES_MINUTES = 10;

    public function registerForm()
    {
        return view('client.register');
    }

    public function clientStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'no_hp' => 'required',
            'alamat' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $otpCode = $this->generateOtpCode();
        $otpExpires = $this->generateOtpExpiry();

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('profiles', 'public');
        }

        // Create user with OTP (not verified yet)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'customer',
            'phone' => $request->no_hp,
            'address' => $request->alamat,
            'photo' => $fotoPath,
            'otp_code' => $otpCode,
            'otp_expires_at' => $otpExpires,
            'is_verified' => false,
        ]);

        session(['pending_user_id' => $user->id, 'otp_sent' => true]);

        if (!$this->sendOtpEmail($user)) {
            return redirect()->route('client.verifyOTPForm')
                ->with('error', 'OTP gagal dikirim ke email. Silakan coba kirim ulang beberapa saat lagi.');
        }

        return redirect()->route('client.verifyOTPForm')
            ->with('info', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function verifyOTPForm()
    {
        $user = $this->getPendingUser();

        if (!$user) {
            return redirect()->route('client.register')->with('error', 'Silakan daftar terlebih dahulu.');
        }

        return view('client.verify_otp', [
            'otpExpiresAt' => $user->otp_expires_at,
            'otpExpiresMinutes' => self::OTP_EXPIRES_MINUTES,
            'maskedEmail' => $this->maskEmail($user->email),
        ]);
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ]);

        $user = $this->getPendingUser();

        if (!$user) {
            return redirect()->route('client.register')->with('error', 'Session expired. Silakan daftar ulang.');
        }

        if ($user->otp_code !== $request->otp_code) {
            return back()->with('error', 'Kode OTP tidak valid.');
        }

        if ($user->otp_expires_at && Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->with('error', 'Kode OTP sudah expired. Silakan minta kode baru.');
        }

        // Verify user
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'is_verified' => true,
        ]);

        // Clear session
        session()->forget(['pending_user_id', 'otp_sent']);

        return redirect()->route('client.loginForm')
            ->with('success', 'Verifikasi berhasil! Silakan login dengan akun Anda.');
    }

    public function resendOTP()
    {
        $user = $this->getPendingUser();

        if (!$user) {
            return back()->with('error', 'Session expired. Silakan daftar ulang.');
        }

        $user->update([
            'otp_code' => $this->generateOtpCode(),
            'otp_expires_at' => $this->generateOtpExpiry(),
        ]);

        if (!$this->sendOtpEmail($user)) {
            return back()->with('error', 'OTP gagal dikirim ke email. Silakan coba lagi beberapa saat lagi.');
        }

        return back()->with('info', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    private function getPendingUser(): ?User
    {
        $userId = session('pending_user_id');

        return $userId ? User::find($userId) : null;
    }

    private function generateOtpCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function generateOtpExpiry(): Carbon
    {
        return Carbon::now()->addMinutes(self::OTP_EXPIRES_MINUTES);
    }

    private function sendOtpEmail(User $user): bool
    {
        try {
            Mail::to($user->email)->send(new ClientOtpMail($user, self::OTP_EXPIRES_MINUTES));
            return true;
        } catch (\Throwable $exception) {
            Log::error('Failed to send OTP email.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    private function maskEmail(string $email): string
    {
        [$name, $domain] = explode('@', $email);
        $visible = substr($name, 0, min(2, strlen($name)));
        $masked = $visible . str_repeat('*', max(strlen($name) - strlen($visible), 1));

        return $masked . '@' . $domain;
    }
}
