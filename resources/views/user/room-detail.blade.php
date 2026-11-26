<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail {{ $room->name }} | Omah Amah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/user/roomdetail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/dashboard.css') }}">
</head>

<body>

    @include('user_partials.sidebar')

    <main class="main-content">

        <!-- TOPBAR -->
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Buka atau ciutkan sidebar" aria-controls="sidebar" aria-expanded="true">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <div>
                    <h1>Detail Kamar</h1>
                    <p>Selamat datang di layanan penghuni Omah Amah.</p>
                </div>
            </div>
            <div class="profile">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}</div>
                <div class="profile-details">
                    <div class="profile-name">{{ auth()->user()->name ?? 'Penghuni' }}</div>
                    <div class="profile-role">Member Omah Amah</div>
                </div>
            </div>
        </header>

        @php
            // Status label & class
            $statusLabel = match($room->availability) {
                'tersedia'         => 'Tersedia',
                'penuh'            => 'Penuh',
                'perlu_konfirmasi' => 'Perlu dikonfirmasi',
                default            => 'Belum dikonfirmasi',
            };
            $statusDotClass = $room->availability === 'tersedia' ? 'is-available' : 'is-pending';

            // Icon map fasilitas
            $iconMap = [
                'AC'                  => 'bi-snow',
                'Meja Belajar'        => 'bi-laptop',
                'Lemari'              => 'bi-door-closed',
                'Kamar Mandi'         => 'bi-droplet',
                'Kamar Mandi Dalam'   => 'bi-droplet-half',
                'Kamar Mandi Bersama' => 'bi-droplet',
                'Keamanan'            => 'bi-shield-check',
                'Area Bersama'        => 'bi-people',
                'Laundry'             => 'bi-basket2-fill',
                'Parkir'              => 'bi-p-square-fill',
                'Kulkas Bersama'      => 'bi-snow2',
                'Dapur Bersama'       => 'bi-cup-hot',
                'Wi-Fi'               => 'bi-wifi',
            ];

            // Foto galeri (pakai image_url, fallback ke unsplash)
            $mainPhoto = $room->image_url
                ?? 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=1200&q=85';
            $sidePhoto1 = 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=800&q=85';
            $sidePhoto2 = 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=800&q=85';
        @endphp

        <!-- Tombol Kembali -->
        <a href="{{ route('user.rooms') }}" class="room-detail-back">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

        <!-- Header Detail Kamar -->
        <section class="room-detail-heading">
            <span class="room-detail-eyebrow">DETAIL KAMAR</span>
            <h2>{{ $room->name }}</h2>
            <p>
                Kenali lebih dekat {{ $room->name }} Omah Amah, pilihan ruang
                untuk beristirahat dan menjalankan aktivitas sehari-hari.
            </p>
        </section>

        <!-- Galeri Foto Kamar -->
        <section class="room-gallery">
            <div class="room-gallery-main">
                <img src="{{ $mainPhoto }}" alt="Tampilan utama {{ $room->name }}">
            </div>
            <div class="room-gallery-side">
                <div class="room-gallery-small">
                    <img src="{{ $sidePhoto1 }}" alt="Tampilan tempat tidur kamar">
                </div>
                <div class="room-gallery-small">
                    <img src="{{ $sidePhoto2 }}" alt="Tampilan interior kamar">
                </div>
            </div>
        </section>

        <!-- Informasi Detail dan Sewa Kamar -->
        <section class="room-detail-information">
            <article class="room-information-card room-about-card">
                <h3>Tentang {{ $room->name }}</h3>
                <p>
                    {{ $room->description ?? $room->name . ' merupakan pilihan kamar di Omah Amah yang dirancang untuk mendukung kegiatan belajar, bekerja, dan beristirahat. Ruangan ini memberikan suasana tenang dan penataan yang praktis.' }}
                </p>
                <p>
                    Informasi pada halaman ini merupakan gambaran awal. Untuk
                    memastikan kondisi kamar, ukuran ruangan, serta fasilitas
                    yang tersedia, silakan hubungi pengelola Omah Amah.
                </p>
            </article>

            <article class="room-information-card room-rental-card">
                <span class="room-rental-eyebrow">
                    <i class="bi bi-info-circle"></i> INFORMASI SEWA
                </span>
                <h3>Harga</h3>
                <p class="room-rental-description">
                    @if($room->monthly_price)
                        <strong style="font-size:1.5rem; color:#111827;">
                            Rp{{ number_format($room->monthly_price, 0, ',', '.') }}
                        </strong>
                        <span style="color:#6b7280;"> / bulan</span>
                    @else
                        Harga sewa belum dicantumkan. Silakan tanyakan langsung
                        untuk mendapatkan informasi biaya dan ketentuan sewa.
                    @endif
                </p>
                <div class="room-availability">
                    <span class="room-availability-dot {{ $statusDotClass }}"></span>
                    <span>{{ $statusLabel }}</span>
                </div>
                <a href="{{ url('/user/chatbot') }}" class="room-ask-ai-button">
                    <i class="bi bi-robot"></i> Tanyakan Kamar Ini ke AI <i class="bi bi-arrow-up-right"></i>
                </a>
                <p class="room-rental-note">
                    <i class="bi bi-shield-check"></i>
                    Harga dan ketersediaan kamar perlu dikonfirmasi kepada pengelola sebelum melakukan penyewaan.
                </p>
            </article>
        </section>

        <!-- Detail yang Perlu Diketahui -->
        <section class="room-important-details">
            <div class="room-important-left">
                <div class="room-important-heading">
                    <h3>Detail yang perlu kamu ketahui</h3>
                    <p>Informasi penting sebelum kamu memutuskan untuk menyewa kamar di Omah Amah.</p>
                </div>
                <div class="room-important-grid">

                    <article class="room-important-card">
                        <div class="room-important-icon"><i class="bi bi-arrows-angle-expand"></i></div>
                        <h4>Ukuran Kamar</h4>
                        <p>
                            @if($room->room_size)
                                Ukuran kamar {{ $room->room_size }}.
                            @else
                                Informasi ukuran kamar menunggu konfirmasi dari pengelola.
                            @endif
                        </p>
                        <span class="room-detail-pending">
                            {{ $room->room_size ?? 'Belum dikonfirmasi' }}
                        </span>
                    </article>

                    <article class="room-important-card">
                        <div class="room-important-icon blue"><i class="bi bi-calendar-check"></i></div>
                        <h4>Ketersediaan</h4>
                        <p>
                            @if($room->availability === 'tersedia')
                                Kamar ini tercatat tersedia saat ini.
                            @else
                                Status kamar perlu dipastikan sebelum melakukan pemesanan.
                            @endif
                        </p>
                        <span class="room-detail-pending">{{ $statusLabel }}</span>
                    </article>

                    <article class="room-important-card">
                        <div class="room-important-icon purple"><i class="bi bi-file-earmark-text"></i></div>
                        <h4>Ketentuan Sewa</h4>
                        <p>Ketentuan durasi sewa dan aturan penghuni perlu dikonfirmasi.</p>
                        <span class="room-detail-pending">Perlu konfirmasi</span>
                    </article>

                    <article class="room-important-card">
                        <div class="room-important-icon orange"><i class="bi bi-wallet2"></i></div>
                        <h4>Biaya &amp; Deposit</h4>
                        <p>Informasi biaya tambahan dan deposit menunggu keterangan pengelola.</p>
                        <span class="room-detail-pending">Belum dikonfirmasi</span>
                    </article>

                </div>
            </div>

            <aside class="room-manager-card">
                <div class="room-manager-icon"><i class="bi bi-headset"></i></div>
                <h3>Hubungi Pengelola</h3>
                <p>Masih memiliki pertanyaan tentang {{ $room->name }}? Hubungi pengelola untuk informasi lebih lengkap.</p>
                <div class="room-manager-divider"></div>
                <div class="room-manager-contact-info">
                    <i class="bi bi-chat-dots"></i> <span>Informasi langsung dari pengelola</span>
                </div>
                <a href="https://wa.me/6281944873028" target="_blank" rel="noopener noreferrer" class="room-manager-button">
                    <i class="bi bi-whatsapp"></i> Hubungi Pengelola <i class="bi bi-arrow-up-right"></i>
                </a>
                <span class="room-manager-note">Silakan tanyakan ketersediaan dan harga kamar terlebih dahulu.</span>
            </aside>
        </section>

        <!-- Section Fasilitas Kamar -->
        <section class="room-detail-facilities-section">
            <div class="room-detail-facilities-header">
                <div>
                    <span class="room-detail-eyebrow">KENYAMANAN PENGHUNI</span>
                    <h3>Fasilitas Kamar</h3>
                    <p>Kenali fasilitas yang menjadi gambaran kebutuhan dasar untuk menunjang aktivitas dan kenyamanan selama tinggal di Omah Amah.</p>
                </div>
            </div>

            <div class="room-detail-facility-items">
                @forelse($room->facilities as $facility)
                    <div class="room-detail-facility-item">
                        <div class="room-detail-facility-item-icon">
                            <i class="bi {{ $iconMap[$facility->name] ?? 'bi-check-circle' }}"></i>
                        </div>
                        <span>{{ $facility->name }}</span>
                    </div>
                @empty
                    <div class="room-detail-facility-item">
                        <div class="room-detail-facility-item-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <span>Fasilitas menyusul</span>
                    </div>
                @endforelse
            </div>

            <p class="room-detail-facilities-description">
                Ketersediaan setiap fasilitas pada kamar yang sebenarnya perlu dikonfirmasi kepada pengelola Omah Amah.
            </p>

            <div class="room-detail-facilities-action">
                <a href="{{ route('user.rooms') }}" class="room-detail-facilities-button">
                    <i class="bi bi-grid"></i> Lihat Kamar dan Fasilitas <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </section>

        <!-- Footer Omah Amah -->
        <footer class="user-footer">
            <div class="user-footer-main">
                <div class="footer-brand">
                    <a href="{{ url('/user/dashboard') }}" class="footer-brand-logo">
                        <span class="footer-brand-icon"><i class="bi bi-house-heart-fill"></i></span>
                        <span>Omah <strong>Amah</strong></span>
                    </a>
                    <p class="footer-description">Temukan tempat tinggal yang nyaman dan sesuai dengan kebutuhanmu. Omah Amah hadir untuk membantu perjalananmu menemukan kos menjadi lebih mudah dan praktis.</p>
                </div>
                <div class="footer-navigation">
                    <h4>Navigasi</h4>
                    <a href="{{ url('/user/dashboard') }}">Dashboard</a>
                    <a href="{{ route('user.inform') }}">Informasi Kost</a>
                    <a href="{{ url('/user/rooms') }}">Kamar &amp; Fasilitas</a>
                    <a href="{{ url('/user/chatbot') }}">Chatbot AI</a>
                </div>
            </div>
            <div class="user-footer-bottom">
                <p>&copy; {{ date('Y') }} Omah Amah. Seluruh hak cipta dilindungi.</p>
                <span>Dibuat dengan <i class="bi bi-heart-fill"></i> untuk kenyamananmu</span>
            </div>
        </footer>

    </main>
    <script src="{{ asset('js/user/dashboard.js') }}" defer></script>
</body>
</html>