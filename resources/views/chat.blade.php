<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ocean WhatsApp Style</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f4f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .main-container { height: 100vh; max-width: 1200px; padding: 20px 0; }
        .chat-app { height: 100%; display: flex; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        /* Kiri: Sidebar */
        .sidebar { width: 35%; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; background: #f8fafc; }
        .sidebar-header { background: #006699; color: white; padding: 15px; }
        .room-list { flex: 1; overflow-y: auto; }
        .room-item { padding: 15px; border-bottom: 1px solid #edf2f7; cursor: pointer; display: block; text-decoration: none; color: #333; }
        .room-item:hover, .room-item.active { background: #e2e8f0; }

        /* Kanan: Ruang Chat */
        .chat-area { width: 65%; display: flex; flex-direction: column; background: #ffffff; }
        .chat-header { background: #006699; color: white; padding: 15px; font-weight: bold; }
        .chat-body { flex: 1; overflow-y: auto; padding: 20px; background-color: #e5eef4; background-image: radial-gradient(#bcd2ee 1px, transparent 1px); background-size: 15px 15px; }
        
        /* Bubble Chat */
        .msg-box { max-width: 70%; margin-bottom: 12px; padding: 8px 14px; border-radius: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.08); }
        .msg-receive { background: #ffffff; align-self: flex-start; border-top-left-radius: 0; }
        .msg-send { background: #d2e6f1; align-self: flex-end; margin-left: auto; border-top-right-radius: 0; }
        .msg-time { font-size: 9px; color: #888; text-align: right; margin-top: 3px; }
    </style>
</head>
<body>

<div class="container main-container">
    <div class="chat-app">
        
        <div class="sidebar">
            <div class="sidebar-header">
                <h5 class="mb-0">OceanChat</h5>
            </div>
            
            <div class="p-3 border-bottom bg-white">
                <form action="{{ url('/create-room') }}" method="POST">
                    @csrf
                    <div class="input-group input-group-sm mb-1">
                        <input type="text" name="room_name" class="form-control" placeholder="Nama Chat / Grup Baru" required>
                        <select name="type" class="form-select bg-light" style="max-width: 100px;">
                            <option value="personal">Personal</option>
                            <option value="group">Grup</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm text-white w-100" style="background-color: #006699;">+ Buat Obrolan Baru</button>
                </form>
            </div>

            <div class="room-list">
    @foreach($rooms as $room)
        <a href="{{ url('/chat?room_id='.$room->id) }}" class="room-item d-flex justify-content-between align-items-center {{ isset($activeRoom) && $activeRoom->id == $room->id ? 'active' : '' }}">
            <div>
                <!-- Logika Pintar Memotong Nama Room Personal -->
                <strong class="d-block">
                    @if($room->type == 'personal')
                        {{ str_replace([' & ', session('user_name')], '', $room->name) }}
                    @else
                        {{ $room->name }}
                    @endif
                </strong>
                <small class="text-muted">
                    @if($room->type == 'group') <span class="badge bg-secondary">Grup</span> @else <span class="badge bg-info text-white">Personal</span> @endif
                </small>
            </div>
        </a>
    @endforeach
</div>
        </div>

        <div class="chat-area">
    @if(isset($activeRoom))
        <div class="chat-header">
            @if($activeRoom->type == 'personal')
                {{ str_replace([' & ', session('user_name')], '', $activeRoom->name) }}
            @else
                {{ $activeRoom->name }}
            @endif
        </div>

        <div class="chat-body d-flex flex-column" id="chatBody">
            @if($messages->count() > 0)
                @foreach($messages as $msg)
                    <div class="msg-box {{ session('user_name') == $msg->sender_name ? 'msg-send' : 'msg-receive' }}">
                        <strong style="font-size: 11px; color: #006699; display: block; margin-bottom: 2px;">{{ $msg->sender_name }}</strong>
                        <div>{{ $msg->message_content }}</div>
                        <div class="msg-time">{{ $msg->created_at->format('H:i') }}</div>
                    </div>
                @endforeach
            @else
                <div class="text-center text-muted my-auto">Belum ada pesan di obrolan ini. Ketik sesuatu yuk!</div>
            @endif
        </div>
        <!-- ====== TAMBAHKAN BOX INPUT PESAN DI SINI (TEPAT DI BAWAH CHAT-BODY) ====== -->
        <div class="p-3 bg-light border-top chat-input-bar">
            <form action="{{ url('/send-message') }}" method="POST" class="d-flex align-items-center gap-2" id="chatForm">
                @csrf
                <!-- Simpan room_id secara tersembunyi agar Laravel tahu pesan ini dikirim ke mana -->
                <input type="hidden" name="room_id" value="{{ $activeRoom->id }}">
                
                <!-- Inputan Teks Pesan -->
                <input type="text" name="message_content" class="form-control form-control-chat" id="messageInput" placeholder="Ketik pesan..." required autocomplete="off">
                
                <!-- Tombol Kirim Biru -->
                <button type="submit" class="btn btn-chat-submit px-4" id="sendButton">Kirim</button>
            </form>
        </div>
        <!-- ========================================================================= -->

        <div class="p-3 bg-light border-top">
            </div>
    @else
        <div class="my-auto text-center text-muted">
            <h3>Selamat Datang di OceanChat</h3>
            <p>Silakan buat obrolan baru di sebelah kiri atau pilih obrolan yang sudah ada.</p>
        </div>
    @endif
</div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/8.3.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>

<script>
    var chatBody = document.getElementById("chatBody");
    if(chatBody) { chatBody.scrollTop = chatBody.scrollHeight; }

    // KONFIGURASI KONEKSI WEBSOCKET REVERB AMAN
window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: '1xvws4oufna5txd14upl', 
    wsHost: '10.64.153.230', // <-- GANTI DENGAN IP LAPTOP KAMU YANG MUNCUL DI IPCONFIG 👍
    wsPort: 8080, 
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});
</script>

@if(isset($activeRoom))
<script>
    // Pastikan channel sudah mengarah ke 'chat.' publik
    window.Echo.channel('chat.' + '{{ $activeRoom->id }}')
        .listen('.MessageSent', (e) => {
            console.log("Sinyal masuk sukses ditangkap!", e);
            
            // 1. INI DIA YANG KETINGGALAN! Mendefinisikan sessionUser agar JavaScript tidak crash
            var sessionUser = "{{ session('user_name') }}";
            
            // 2. Sekarang pengecekan ini akan berjalan lancar tanpa error
            var isSend = (sessionUser === e.message.sender_name) ? 'msg-send' : 'msg-receive';

            // Ambil waktu jam menit saat ini
            var now = new Date();
            var timeStr = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');

            // Susun template bubble chat
            var newBubble = `
                <div class="msg-box ${isSend}">
                    <strong style="font-size: 11px; color: #006699; display: block; margin-bottom: 2px;">${e.message.sender_name}</strong>
                    <div>${e.message.message_content}</div>
                    <div class="msg-time">${timeStr}</div>
                </div>
            `;

            var chatBodyElement = document.getElementById("chatBody");
            if(chatBodyElement) {
                // Hapus teks bawaan jika obrolan masih kosong
                if(chatBodyElement.innerHTML.includes('Belum ada pesan')) {
                    chatBodyElement.innerHTML = '';
                }
                
                // Cetak pesan secara instan ke layar
                chatBodyElement.insertAdjacentHTML('beforeend', newBubble);
                chatBodyElement.scrollTop = chatBodyElement.scrollHeight;
            }
        });
</script>
@endif
</body>
</html>