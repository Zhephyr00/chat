<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Message;

class MessageController extends Controller
{
    // 1. Menampilkan halaman utama chat beserta daftar room
    public function index(Request $request)
{
    // 1. Ambil nama user yang sedang login dari session
    $currentUser = session('user_name');

    // Jika belum login/isi nama, arahkan ke halaman login chat
    if (!$currentUser) {
        return view('login_chat');
    }

    // 2. FILTER ROOM: Tampilkan semua Grup, TAPI untuk Personal cuma tampilkan yang namanya mengandung nama user ini
    $rooms = Room::where('type', 'group')
        ->orWhere(function($query) use ($currentUser) {
            $query->where('type', 'personal')
                  ->where('name', 'LIKE', '%' . $currentUser . '%');
        })
        ->get();

    // Logika mengambil pesan yang sudah ada
    $activeRoom = null;
    $messages = collect();
    if ($request->has('room_id')) {
        $activeRoom = Room::find($request->room_id);
        if ($activeRoom) {
            $messages = Message::where('room_id', $activeRoom->id)->get();
        }
    }

    return view('chat', compact('rooms', 'activeRoom', 'messages'));
}

    // 2. Fungsi untuk membuat Room/Grup Baru
    public function createRoom(Request $request)
{
    $request->validate([
        'room_name' => 'required|string|max:255',
        'type' => 'required|in:personal,group'
    ]);

    // Ambil nama kamu yang sedang login dari session
    $currentUser = session('user_name') ?? 'User';
    $roomName = $request->room_name;

    // JIKA PERSONAL: Paksa formatnya disimpan menjadi "Bita & Assa"
    if ($request->type == 'personal') {
        $roomName = $currentUser . ' & ' . $request->room_name;
    }

    \App\Models\Room::create([
        'name' => $roomName,
        'type' => $request->type
    ]);

    return redirect()->back();
}

    // 3. Fungsi untuk mengirim pesan ke room tertentu
    public function store(Request $request)
{
    // 1. Validasi inputan biar wajib diisi
    $request->validate([
        'room_id' => 'required|exists:rooms,id',
        'message_content' => 'required|string',
    ]);

    // 2. Ambil nama pengirim dari session login
    $senderName = session('user_name') ?? 'Anonim';

    // 3. Simpan pesan baru ke database
    $message = \App\Models\Message::create([
        'room_id' => $request->room_id,
        'sender_name' => $senderName,
        'message_content' => $request->message_content,
    ]);

    // 4. KITA UBAH DI SINI: Buang ->toOthers() agar sinyal dipantulkan secara adil ke SEMUA server/browser!
    event(new \App\Events\MessageSent($message));

    // 5. Kembalikan halaman ke ruang chat semula setelah klik kirim
    return redirect()->back();
}
    
}