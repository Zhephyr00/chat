<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    // Tambahkan 'room_id' di dalam array fillable agar tidak error default value lagi
    protected $fillable = ['room_id', 'sender_name', 'message_content'];

    // Relasi balik: Setiap pesan dimiliki oleh sebuah Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}