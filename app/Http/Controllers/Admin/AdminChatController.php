<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminChatController extends Controller
{
    // Menampilkan DAFTAR USER yang pernah chat
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $hasChatReadAtColumn = Schema::hasColumn('chats', 'read_at');

        $kontak = Chat::with('user')
            ->select(
                'user_id',
                DB::raw('MAX(created_at) as last_chat'),
                DB::raw(
                    $hasChatReadAtColumn
                        ? "SUM(CASE WHEN pengirim = 'user' AND read_at IS NULL THEN 1 ELSE 0 END) as unread_count"
                        : "0 as unread_count"
                )
            )
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->groupBy('user_id')
            ->orderBy('last_chat', 'desc')
            ->get();

        return view('admin.chat.index', compact('kontak', 'search'), [
            'title' => 'chat',
        ]);
    }

    // Menampilkan ISI CHAT dengan user tertentu
    public function show($id)
    {
        $user = User::findOrFail($id);

        if (Schema::hasColumn('chats', 'read_at')) {
            Chat::where('user_id', $id)
                ->where('pengirim', 'user')
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        $messages = Chat::where('user_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.chat.show', compact('user', 'messages'),[
            'title' => 'chat',
        ]);
    }

    public function send(Request $request)
{
    $payload = [
        // Ambil ID Customer dari input hidden di form, BUKAN Auth::id()
        'user_id' => $request->user_id, 
        'pesan' => $request->pesan,
        'pengirim' => 'admin', // Tandai kalau yang ngetik Admin
    ];

    if (Schema::hasColumn('chats', 'read_at')) {
        $payload['read_at'] = null;
    }

    Chat::create($payload);

    return back();
}
}
