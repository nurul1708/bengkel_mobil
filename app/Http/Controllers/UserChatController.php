<?php
namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class UserChatController extends Controller
{
    public function index()
    {
        $clientId = auth('client')->id();

        if (!$clientId) {
            return redirect()->route('client.loginForm');
        }

        if (Schema::hasColumn('chats', 'read_at')) {
            Chat::where('user_id', $clientId)
                ->where('pengirim', 'admin')
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        // Ambil pesan hanya milik user yang login
        $messages = Chat::where('user_id', $clientId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('fe.chat.index', compact('messages'), [
            'title' => 'chat',
        ]);
    }

    public function store(Request $request)
    {
        $clientId = auth('client')->id();

        if (!$clientId) {
            return redirect()->route('client.loginForm');
        }

        $request->validate([
            'pesan' => 'required'
        ]);

        $payload = [
            'user_id' => $clientId,
            'pesan' => $request->pesan,
            'pengirim' => 'user',
        ];

        if (Schema::hasColumn('chats', 'read_at')) {
            $payload['read_at'] = null;
        }

        Chat::create($payload);

        return back();
    }
}
