<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\TransactionSparepart;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('role', 'like', '%' . $search . '%');
            })
            ->latest('id')
            ->get();

        return view('users.index', compact('users', 'search'), [
            'title' => 'Users'
        ]);
     }

        public function create()
        {
            $users = User::all();
            return view('users.create', [
                'title' => 'Users'
            ]);
        }

        public function store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'phone'    => 'required',
        'address'  => 'required',
        'password' => 'required|min:6', // Pastikan input ini ada di Blade
        'role'     => 'required|in:admin,owner,kasir,mekanik,customer'
    ]);

    User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'phone'    => $request->phone,
        'address'  => $request->address,
        'password' => bcrypt($request->password),
        'role'     => $request->role
    ]);

    return redirect()->route('users.index')->with('success', 'User berhasil ditambah');
}
        public function show(string $id)
        {
            $users = User::findOrFail($id);
            return view('users.show', compact('users'), [
                'title' => 'Users'
            ]);
        }
        
        public function edit(string $id)
        {
            $users = User::findOrFail($id);
            return view('users.edit', compact('users'), [
                'title' => 'Users'
            ]);
        }

        public function update(Request $request, string $id)
        {
            $users = User::findOrFail($id);

            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$users->id,
                'phone' => 'required',
                'address' => 'required',
                'password' => 'nullable|min:6',
                'role' => 'required|in:admin,owner,kasir,mekanik,customer'
            ]);

            $users->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => $request->password ? bcrypt($request->password) : $users->password,
                'role' => $request->role
            ]);

            return redirect('/admin/users')->with('success','User berhasil diupdate');
        }

        public function destroy(string $id)
        {
            $users = User::findOrFail($id);

            DB::transaction(function () use ($users) {
                $bookingIds = Booking::where('user_id', $users->id)->pluck('id');

                $transactionIds = Transaction::whereIn('booking_id', $bookingIds)
                    ->orWhere('mekanik_id', $users->id)
                    ->orWhere('kasir_id', $users->id)
                    ->pluck('id')
                    ->unique()
                    ->values();

                if ($transactionIds->isNotEmpty()) {
                    Payment::whereIn('transaction_id', $transactionIds)->delete();
                    TransactionSparepart::whereIn('transaction_id', $transactionIds)->delete();
                    Transaction::whereIn('id', $transactionIds)->delete();
                }

                Booking::where('user_id', $users->id)->delete();
                Vehicle::where('user_id', $users->id)->delete();
                $users->delete();
            });

            return redirect('/admin/users')->with('success','User berhasil dihapus');
        }


}
