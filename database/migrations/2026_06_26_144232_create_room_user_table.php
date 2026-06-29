<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('room_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('room_id')->constrained()->onDelete('cascade');
        // Kita simpan nama user secara string dulu agar simpel tanpa tabel users terpisah jika belum ada
        $table->string('user_name'); 
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_user');
    }
};
