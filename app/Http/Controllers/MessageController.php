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
        // Ambil nama user yang sedang login dari session
        $currentUser = session('user_name');

        // Jika belum login/isi nama, arahkan ke halaman login chat
        if (!$currentUser) {
            return view('login_chat');
        }

        // FILTER ROOM: Tampilkan semua Grup, TAPI untuk Personal cuma tampilkan yang namanya mengandung nama user ini
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

        Room::create([
            'name' => $roomName,
            'type' => $request->type
        ]);

        return redirect()->back();
    }

    // 3. Fungsi untuk mengirim pesan ke room tertentu (Sudah LOSS tanpa toOthers)
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'message_content' => 'required|string',
        ]);

        $senderName = session('user_name') ?? 'Anonim';

        $message = Message::create([
            'room_id' => $request->room_id,
            'sender_name' => $senderName,
            'message_content' => $request->message_content,
        ]);

        // Pemicu sinyal sinkron instan
        event(new \App\Events\MessageSent($message));

        return redirect()->back();
    }

    // 4. Fungsi untuk memproses inputan nama di awal (Kembali utuh!)
    public function setNama(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50'
        ]);
        
        session(['user_name' => $request->nama]); 
        
        return redirect('/chat');
    }

    // 5. Fungsi untuk logout
    public function logout()
    {
        session()->forget('user_name'); 
        return redirect('/chat');       
    }
}