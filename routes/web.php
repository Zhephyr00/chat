<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

Route::get('/', function () {
    return view('welcome');
});

// Jalur utama untuk melihat halaman chat & memilih room
Route::get('/chat', [MessageController::class, 'index']);

// Jalur untuk memproses pembuatan room/grup baru
Route::post('/create-room', [MessageController::class, 'createRoom']);

// Jalur untuk mengirim pesan di dalam room
Route::post('/send-message', [MessageController::class, 'store']);

Route::post('/set-nama', [MessageController::class, 'setNama']);

Route::get('/logout', [MessageController::class, 'logout']);