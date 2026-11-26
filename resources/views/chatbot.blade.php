<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Chatbot AI | Omah Amah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/room.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
</head>
<body>
    @include('partials.sidebar')

    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Buka atau ciutkan sidebar" aria-controls="sidebar" aria-expanded="true">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div>
                    <h1>Chatbot AI</h1>
                    <p>Selamat datang di layanan penghuni Omah Amah.</p>
                </div>
            </div>

            <div class="profile">
                <div class="avatar">P</div>
                <div class="profile-details">
                    <div class="profile-name">Penghuni</div>
                    <div class="profile-role">Member Omah Amah</div>
                </div>
            </div>
        </header>

        <div class="row g-3">
            
            <!-- KOLOM KIRI: CHAT ROOM (TEMPAT USER MENGKETIK) -->
            <div class="col-lg-7">
                <section class="content-card" style="height: 650px; display: flex; flex-direction: column;">
                    
                    <!-- Header Chat -->
                    <div class="question-history-header" style="border-bottom: 1px solid #edf0f5; padding-bottom: 15px; margin-bottom: 15px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 45px; height: 45px; background: #edf5ff; color: #3974ad; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                <i class="bi bi-robot"></i>
                            </div>
                            <div>
                                <h2 class="section-title" style="margin: 0;">Amah AI</h2>
                                <p style="margin: 0; font-size: 12px; color: #32b779;"><i class="bi bi-circle-fill" style="font-size: 8px;"></i> Online</p>
                            </div>
                        </div>
                    </div>

                    <!-- Area Chat (Pesan) -->
                    <div id="chatBox" style="flex: 1; overflow-y: auto; padding: 10px 0; display: flex; flex-direction: column; gap: 15px; background: #f8fafc; border-radius: 12px; padding: 20px;">
                        <!-- Pesan Selamat Datang -->
                        <div style="background: #fff; border: 1px solid #e2e8f0; padding: 12px 15px; border-radius: 0 15px 15px 15px; max-width: 80%; align-self: flex-start; font-size: 13px; color: #334155;">
                            Halo! Saya Amah AI, asisten virtual Omah Amah. Ada yang bisa saya bantu terkait info kamar, harga, fasilitas, atau laundry?
                        </div>
                    </div>

                    <!-- Input Chat -->
                    <form id="chatForm" style="display: flex; gap: 10px; margin-top: 15px;">
                        <input type="text" id="chatInput" class="form-control" placeholder="Ketik pertanyaanmu di sini..." required autocomplete="off" style="border-radius: 20px; padding: 12px 20px; border: 1px solid #cbd5e1;">
                        <button type="submit" class="btn btn-primary" style="border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background-color: #426b50; border-color: #426b50;">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                </section>
            </div>

            <!-- KOLOM KANAN: RIWAYAT PERTANYAAN -->
            <div class="col-lg-5">
                <section class="content-card question-history-card h-100" style="height: 650px; display: flex; flex-direction: column;">
                    <div class="question-history-header" style="flex-shrink: 0;">
                        <div>
                            <h2 class="section-title">Riwayat Chat</h2>
                            <p>Pesan yang tersimpan di database.</p>
                        </div>
                        <span class="question-total" id="totalPertanyaan">0 Pesan</span>
                    </div>

                    <!-- Filter -->
                    <div class="question-filter-tabs" style="flex-shrink: 0;">
                        <button type="button" class="question-filter active" data-filter="semua">Semua</button>
                        <button type="button" class="question-filter" data-filter="belum">Belum Terjawab</button>
                        <button type="button" class="question-filter" data-filter="tinjau">Perlu Ditinjau</button>
                    </div>

                    <!-- List Riwayat (Bisa di-scroll) -->
                    <div class="question-history-list" id="questionHistoryList" style="flex: 1; overflow-y: auto;">
                        <div class="text-center py-4 text-muted">Memuat data...</div>
                    </div>
                </section>
            </div>

        </div>

        <footer class="dashboard-footer">
            © 2026 Omah Amah · Smart Boarding House
        </footer>
    </main>

    <script src="{{ asset('js/dashboard.js') }}" defer></script>

    <script>

document.addEventListener('DOMContentLoaded', function() {
    let allQuestions = [];
    let currentFilter = 'semua';

    const chatBox = document.getElementById('chatBox');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const listContainer = document.getElementById('questionHistoryList');

    // ==========================================
    // 1. FUNGSI MENAMPILKAN PESAN CHAT
    // ==========================================
    function addChatMessage(message, sender) {
        const bubble = document.createElement('div');

        bubble.style.cssText = `
            padding: 12px 15px;
            border-radius: 12px;
            max-width: 80%;
            font-size: 13px;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            line-height: 1.6;
        `;

        if (sender === 'user') {
            bubble.style.background = '#426b50';
            bubble.style.color = '#fff';
            bubble.style.alignSelf = 'flex-end';
        } else {
            bubble.style.background = '#fff';
            bubble.style.color = '#334155';
            bubble.style.border = '1px solid #e2e8f0';
            bubble.style.alignSelf = 'flex-start';
        }

        bubble.textContent = message;

        chatBox.appendChild(bubble);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // ==========================================
    // 2. LOGIKA KIRIM PESAN CHAT
    // ==========================================
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const message = chatInput.value.trim();

        if (!message) return;

        // Tampilkan pesan user
        addChatMessage(message, 'user');

        chatInput.value = '';
        chatInput.disabled = true;

        // Tampilkan status mengetik
        const typing = document.createElement('div');

        typing.textContent = 'Amah AI sedang mengetik...';

        typing.style.cssText = `
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 12px 15px;
            border-radius: 12px;
            max-width: 80%;
            font-size: 13px;
            color: #94a3b8;
            align-self: flex-start;
        `;

        chatBox.appendChild(typing);
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            const response = await fetch('/api/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector(
                        'meta[name="csrf-token"]'
                    ).getAttribute('content')
                },
                body: JSON.stringify({
                    message: message,
                    sender_name: 'Penghuni'
                })
            });

            const res = await response.json();

            // Hapus status mengetik
            typing.remove();

            if (!response.ok || !res.success) {
                addChatMessage(
                    res.message || 'Maaf, Amah AI sedang mengalami kendala.',
                    'bot'
                );
                return;
            }

            // Tampilkan balasan chatbot
            addChatMessage(res.reply, 'bot');

            // Perbarui riwayat chat
            fetchQuestions();

        } catch (error) {
            console.error('Error chatbot:', error);

            typing.remove();

            addChatMessage(
                'Maaf, tidak dapat terhubung ke server. Silakan coba kembali.',
                'bot'
            );

        } finally {
            chatInput.disabled = false;
            chatInput.focus();
        }
    });

    // ==========================================
    // 3. AMBIL DATA RIWAYAT CHAT
    // ==========================================
    function fetchQuestions() {
        // PERBAIKAN: Menambahkan ?source=chatbot_ai agar hanya mengambil data dari AI
        fetch('/api/inquiries?source=chatbot_ai', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal mengambil data riwayat.');
            }

            return response.json();
        })
        .then(res => {
            if (res.success) {
                allQuestions = res.data;

                document.getElementById('totalPertanyaan').textContent =
                    `${allQuestions.length} Pesan`;

                renderList();
            }
        })
        .catch(error => {
            console.error('Gagal mengambil riwayat:', error);

            listContainer.innerHTML =
                '<div class="text-center py-4 text-muted">Gagal memuat riwayat chat.</div>';
        });
    }

    // ==========================================
    // 4. TAMPILKAN RIWAYAT CHAT
    // ==========================================
    function renderList() {
        listContainer.innerHTML = '';

        let filtered = allQuestions;

        if (currentFilter === 'belum') {
            filtered = allQuestions.filter(q => q.status === 'baru');

        } else if (currentFilter === 'tinjau') {
            filtered = allQuestions.filter(q => q.status === 'diproses');
        }

        if (filtered.length === 0) {
            listContainer.innerHTML = `
                <div class="text-center py-4 text-muted">
                    Tidak ada pesan.
                </div>
            `;
            return;
        }

        filtered.forEach(q => {
            let statusClass = 'status-unanswered';
            let statusText = 'Belum terjawab';

            if (q.status === 'diproses') {
                statusClass = 'status-review';
                statusText = 'Perlu ditinjau';

            } else if (q.status === 'selesai') {
                statusClass = 'status-answered';
                statusText = 'Terjawab';
            }

            const dateObj = new Date(q.created_at);

            const timeStr = dateObj.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            }) + ' WIB';

            const article = document.createElement('article');

            article.className = 'question-history-item';
            article.style.cursor = 'pointer';
            article.style.marginBottom = '10px';

            const main = document.createElement('div');
            main.className = 'question-item-main';

            const icon = document.createElement('div');
            icon.className = 'question-item-icon';
            icon.innerHTML = '<i class="bi bi-chat-left-text"></i>';

            const content = document.createElement('div');
            content.className = 'question-item-content';

            const question = document.createElement('h3');
            question.textContent = q.question;

            const sender = document.createElement('p');
            sender.textContent = `Pertanyaan dari ${q.sender_name}`;

            const time = document.createElement('span');
            time.className = 'question-item-time';
            time.textContent = timeStr;

            const clock = document.createElement('i');
            clock.className = 'bi bi-clock';

            time.prepend(clock);
            time.append(' ');

            content.append(question, sender, time);
            main.append(icon, content);
            article.appendChild(main);

            listContainer.appendChild(article);
        });
    }

    // ==========================================
    // 5. FILTER RIWAYAT CHAT
    // ==========================================
    document.querySelectorAll('.question-filter').forEach(btn => {
        btn.addEventListener('click', function() {

            document.querySelectorAll('.question-filter').forEach(b => {
                b.classList.remove('active');
            });

            this.classList.add('active');

            currentFilter = this.getAttribute('data-filter');

            renderList();
        });
    });

    // ==========================================
    // 6. JALANKAN SAAT HALAMAN DIBUKA
    // ==========================================
    fetchQuestions();

});
    </script>
</body>
</html>