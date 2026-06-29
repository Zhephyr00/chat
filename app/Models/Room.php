<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    // Tambahkan baris fillable ini untuk mengizinkan input nama dan tipe room
    protected $fillable = ['name', 'type'];

    // Relasi ke tabel messages (biarkan tetap ada jika sudah kamu tulis)
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}