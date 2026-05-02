<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ClientBookingStatusMail;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class BookingController extends Controller
{
    private function sendBookingStatusEmail(Booking $booking): void
    {
        $booking->loadMissing(['user', 'vehicle', 'service']);

        $client = $booking->user;

        if (!$client?->email || !in_array($booking->status, ['confirmed', 'cancelled'], true)) {
            return;
        }

        try {
            Mail::to($client->email)->send(new ClientBookingStatusMail($booking));
        } catch (Throwable $e) {
            Log::error('Failed sending booking status email', [
                'booking_id' => $booking->id,
                'email' => $client->email,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $search = trim((string) $request->query('search'));

$query = Booking::with('user', 'vehicle', 'service', 'mekanik');

        if ($user && $user->role === 'kasir') {
            $query->whereIn('status', ['completed', 'paid']);
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('service', function ($serviceQuery) use ($search) {
                    $serviceQuery->where('service_name', 'like', '%' . $search . '%');
                })->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                    $vehicleQuery->where('brand', 'like', '%' . $search . '%')
                        ->orWhere('model', 'like', '%' . $search . '%')
                        ->orWhere('license_plate', 'like', '%' . $search . '%');
                });
            });
        }

        $bookings = $query->latest('id')->get();

        return view('booking.index', compact('bookings', 'search'), [
            'title' => 'Booking'
        ]);
    }

public function show($id)
    {
        $booking = Booking::with('user', 'vehicle', 'service', 'mekanik')->findOrFail($id);
        return view('booking.detail', compact('booking'), [
            'title' => 'Booking'
        ]);
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        $booking = Booking::findOrFail($id);

        $booking->update([
            'status' => $request->status
        ]);

        $this->sendBookingStatusEmail($booking->fresh());

        return redirect('/admin/booking')->with('success', 'Status booking berhasil diperbarui');
    }

public function proses($id)
    {
        $booking = Booking::findOrFail($id);

        // Get the logged-in mechanic
        $mekanikId = Auth::user()->id;

        $booking->update([
            'status' => 'in_progress',
            'mekanik_id' => $mekanikId
        ]);

        return redirect('/admin/booking')->with('success', 'Service dimulai');
    }

    public function selesai($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update([
            'status' => 'completed'
        ]);

        return redirect('/admin/booking')->with('success', 'Service selesai, menunggu pembayaran');
    }
}
