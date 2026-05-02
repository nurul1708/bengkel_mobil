<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory;

    // Nama tabelnya
    protected $table = 'chats';

    // IZIN buat masukin data secara massal (PENTING!)
    protected $fillable = [
        'user_id',
        'pesan',
        'pengirim',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // Relasi ke User (Opsional tapi bagus buat ada)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
