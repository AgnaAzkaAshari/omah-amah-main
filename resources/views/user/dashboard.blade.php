<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard | Omah Amah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS Tambahan untuk Memperbaiki Layout -->
    <style>
        /* Mencegah teks ter-highlight (ketik-ketik) saat elemen diklik */
        .room-card, .btn-hero-primary, .btn-hero-secondary, .btn-ask-omah, .profile, .room-detail-button {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
        
        /* Memaksa grid menjadi 3 kolom yang rapi dan memenuhi layar */
        .room-selection-grid {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important; 
            gap: 24px !important;
        }

        /* Agar responsif di HP/Tablet */
        @media (max-width: 992px) {
            .room-selection-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
        @media (max-width: 768px) {
            .room-selection-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/user/dashboard.css') }}">
</head>

<body>

    @include('user_partials.sidebar')

    <main class="main-content">

        <!-- TOPBAR -->
        <header class="topbar">

            <div class="topbar-left">

                <button type="button"
                    class="sidebar-toggle"
                    id="sidebarToggle"
                    aria-label="Buka atau ciutkan sidebar"
                    aria-controls="sidebar"
                    aria-expanded="true">

                    <span></span>
                    <span></span>
                    <span></span>

                </button>

                <div>
                    <h1>Dashboard</h1>
                    <p>Selamat datang di layanan penghuni Omah Amah.</p>
                </div>

            </div>


            <!-- PROFIL PENGHUNI -->
            <a href="{{ route('user.profile') }}"
               class="profile"
               style="text-decoration:none; color:inherit; cursor:pointer;">

                @if(auth()->check() && auth()->user()->photo)

                    <img
                        src="{{ asset('storage/' . auth()->user()->photo) }}"
                        class="avatar"
                        alt="Foto Profil"
                        style="width:45px;height:45px;border-radius:50%;object-fit:cover;"
                    >

                @else

                    <div class="avatar">
                        {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'P' }}
                    </div>

                @endif

                <div class="profile-details">

                    <div class="profile-name">
                        {{ auth()->check() ? auth()->user()->name : 'Penghuni' }}
                    </div>

                    <div class="profile-role">
                        Member Omah Amah
                    </div>

                </div>

            </a>

        </header>


        <!-- HERO SECTION -->
        <section class="user-hero">

            <div class="hero-content">

                <div class="hero-eyebrow">
                    <span>Teman menemukan tempat tinggal</span>
                </div>

                <h2 class="hero-title">
                    Temukan Kost Nyaman yang Sesuai dengan Kebutuhanmu
                </h2>

                <p class="hero-description">
                    Temukan tempat tinggal yang nyaman untuk beristirahat
                    dan beraktivitas. Jelajahi pilihan kamar, kenali fasilitas,
                    dan dapatkan informasi kost dengan lebih mudah bersama
                    Omah Amah.
                </p>

                <div class="hero-actions">

                    <a href="{{ route('user.inform') }}" class="btn-hero-primary">
                        <span>Lihat Kamar</span>
                        <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>

                    <a href="{{ url('/user/chatbot') }}" class="btn-hero-secondary">
                        <i class="bi bi-robot"></i>
                        <span>Tanya Amah AI</span>
                    </a>

                </div>

                <div class="hero-note">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Informasi kost lebih mudah dalam satu tempat</span>
                </div>

            </div>

            <div class="hero-visual">

                <div class="hero-image-wrapper">

                    <img
                        src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1200&q=85"
                        alt="Kamar kost modern yang nyaman"
                        class="hero-image"
                    >

                </div>

                <div class="hero-decoration decoration-one"></div>
                <div class="hero-decoration decoration-two"></div>

            </div>

        </section>


        <!-- INFORMASI KOST -->
        <section class="kost-information">

            <div class="section-heading">

                <div>
                    <span class="section-eyebrow">INFORMASI PENGHUNI</span>
                    <h2>Kenali Sebelum Memilih</h2>
                    <p>
                        Informasi singkat untuk membantumu mengenal hunian Omah Amah.
                    </p>
                </div>

            </div>

            <div class="kost-info-grid">

                <!-- LOKASI -->
                <div class="kost-info-card">

                    <div class="kost-info-icon icon-location">
                        <i class="bi bi-geo-alt"></i>
                    </div>

                    <div class="kost-info-content">

                        <span class="kost-info-label">Lokasi Kost</span>

                        <h3 id="infoLokasi">Memuat...</h3>

                        <p>
                            <i class="bi bi-clock"></i>
                            <span id="infoLokasiStatus">-</span>
                        </p>

                    </div>

                </div>


                <!-- HARGA -->
                <div class="kost-info-card">

                    <div class="kost-info-icon icon-price">
                        <i class="bi bi-wallet2"></i>
                    </div>

                    <div class="kost-info-content">

                        <span class="kost-info-label">Harga Kost</span>

                        <h3 id="infoHarga">Memuat...</h3>

                        <p>
                            <i class="bi bi-clock"></i>
                            <span id="infoHargaStatus">-</span>
                        </p>

                    </div>

                </div>


                <!-- FASILITAS -->
                <div class="kost-info-card">

                    <div class="kost-info-icon icon-facility">
                        <i class="bi bi-house-check"></i>
                    </div>

                    <div class="kost-info-content">

                        <span class="kost-info-label">Fasilitas</span>

                        <h3 id="infoFasilitas">Memuat...</h3>

                        <p>
                            <i class="bi bi-clock"></i>
                            <span id="infoFasilitasStatus">-</span>
                        </p>

                    </div>

                </div>


                <!-- KETERSEDIAAN -->
                <div class="kost-info-card">

                    <div class="kost-info-icon icon-status">
                        <i class="bi bi-door-open"></i>
                    </div>

                    <div class="kost-info-content">

                        <span class="kost-info-label">Ketersediaan Kamar</span>

                        <h3 id="infoKetersediaan">Memuat...</h3>

                        <p>
                            <i class="bi bi-clock"></i>
                            <span id="infoKetersediaanStatus">-</span>
                        </p>

                    </div>

                </div>

            </div>

        </section>


        <!-- PILIHAN KAMAR -->
        <section class="room-selection">

            <div class="room-section-heading">

                <div>
                    <span class="section-eyebrow">TEMUKAN HUNIANMU</span>
                    <h2>Pilihan Kamar untukmu</h2>
                    <p>
                        Jelajahi pilihan kamar dan temukan ruang nyaman
                        yang sesuai dengan kebutuhanmu.
                    </p>
                </div>

                <a href="{{ route('user.inform') }}" class="room-view-all">
                    Lihat semua
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>


            <div class="room-selection-grid">

                <!-- KAMAR A (STANDARD) -->
                <article class="room-card">

                    <div class="room-image-wrapper">

                        <img
                            id="imgKamarA"
                            src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=700&q=80"
                            alt="Contoh kamar tipe A"
                            class="room-image"
                            loading="lazy"
                        >

                        <span class="room-type-badge">Tipe A</span>

                    </div>

                    <div class="room-card-body">

                        <h3 id="namaKamarA">Kamar Standard</h3>

                        <div class="room-price">
                            <strong id="hargaKamarA">Rp —</strong>
                            <span>/ bulan</span>
                        </div>

                        <p class="room-description" id="descKamarA">
                            Kamar nyaman dengan desain minimalis untuk menunjang
                            istirahat dan aktivitas harianmu.
                        </p>

                        <div class="room-facilities">
                            <span><i class="bi bi-wifi"></i> Wi-Fi</span>
                            <span><i class="bi bi-snow"></i> AC</span>
                            <span><i class="bi bi-door-closed"></i> Privat</span>
                        </div>

                        <a href="{{ route('user.rooms') }}" class="room-detail-button">
                            <span>Lihat Detail</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </article>


                <!-- KAMAR B (NYAMAN) -->
                <article class="room-card">

                    <div class="room-image-wrapper">

                        <img
                            id="imgKamarB"
                            src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=700&q=80"
                            alt="Contoh kamar tipe B"
                            class="room-image"
                            loading="lazy"
                        >

                        <span class="room-type-badge">Tipe B</span>

                    </div>

                    <div class="room-card-body">

                        <h3 id="namaKamarB">Kamar Nyaman</h3>

                        <div class="room-price">
                            <strong id="hargaKamarB">Rp —</strong>
                            <span>/ bulan</span>
                        </div>

                        <p class="room-description" id="descKamarB">
                            Pilihan kamar praktis dengan suasana tenang
                            untuk belajar, bekerja, dan beristirahat.
                        </p>

                        <div class="room-facilities">
                            <span><i class="bi bi-wifi"></i> Wi-Fi</span>
                            <span><i class="bi bi-lightning"></i> Listrik</span>
                            <span><i class="bi bi-door-closed"></i> Privat</span>
                        </div>

                        <a href="{{ route('user.rooms') }}" class="room-detail-button">
                            <span>Lihat Detail</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </article>


                <!-- KAMAR C (PRIVAT) -->
                <article class="room-card">

                    <div class="room-image-wrapper">

                        <img
                            id="imgKamarC"
                            src="https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=700&q=80"
                            alt="Contoh kamar tipe C"
                            class="room-image"
                            loading="lazy"
                        >

                        <span class="room-type-badge">Tipe C</span>

                    </div>

                    <div class="room-card-body">

                        <h3 id="namaKamarC">Kamar Privat</h3>

                        <div class="room-price">
                            <strong id="hargaKamarC">Rp —</strong>
                            <span>/ bulan</span>
                        </div>

                        <p class="room-description" id="descKamarC">
                            Kamar dengan penataan sederhana dan nyaman
                            untuk mendukung keseharianmu.
                        </p>

                        <div class="room-facilities">
                            <span><i class="bi bi-wifi"></i> Wi-Fi</span>
                            <span><i class="bi bi-lamp"></i> Lampu</span>
                            <span><i class="bi bi-door-closed"></i> Privat</span>
                        </div>

                        <a href="{{ route('user.rooms') }}" class="room-detail-button">
                            <span>Lihat Detail</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </article>

            </div>

        </section>


        <!-- KEUNGGULAN OMAH AMAH -->
        <section class="why-omah-section">

            <div class="why-omah-heading">

                <span class="section-eyebrow">KEUNGGULAN KAMI</span>

                <h2>Kenapa Memilih Omah Amah?</h2>

                <p>
                    Kami membantu kamu menemukan tempat tinggal yang nyaman,
                    praktis, dan sesuai dengan kebutuhanmu.
                </p>

            </div>

            <div class="why-omah-grid">

                <div class="why-omah-card">
                    <div class="why-omah-icon icon-green">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <h3>Informasi Kamar yang Jelas</h3>
                    <p>
                        Lihat informasi kamar, harga, dan fasilitas
                        untuk membantu menentukan pilihan.
                    </p>
                </div>

                <div class="why-omah-card">
                    <div class="why-omah-icon icon-blue">
                        <i class="bi bi-house-check"></i>
                    </div>
                    <h3>Pilihan Kamar Nyaman</h3>
                    <p>
                        Temukan pilihan kamar yang dapat disesuaikan
                        dengan kebutuhan tempat tinggalmu.
                    </p>
                </div>

                <div class="why-omah-card">
                    <div class="why-omah-icon icon-orange">
                        <i class="bi bi-robot"></i>
                    </div>
                    <h3>Bantuan Amah AI</h3>
                    <p>
                        Dapatkan bantuan untuk mencari informasi kost
                        dengan lebih praktis.
                    </p>
                </div>

                <div class="why-omah-card">
                    <div class="why-omah-icon icon-purple">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h3>Akses Informasi Praktis</h3>
                    <p>
                        Jelajahi informasi kost kapan saja dengan mudah
                        melalui satu platform.
                    </p>
                </div>

            </div>

        </section>


        <!-- BANTUAN AMAH AI -->
        <section class="ask-omah-section">

            <div class="ask-omah-icon">
                <i class="bi bi-chat-square-heart-fill"></i>
            </div>

            <div class="ask-omah-content">

                <h3>Bingung Memilih Kos?</h3>

                <p>
                    Jangan khawatir! Tanyakan kebutuhanmu kepada Amah AI
                    untuk membantu menemukan informasi kos yang sesuai.
                </p>

            </div>

            <div class="ask-omah-action">

                <a href="{{ url('/user/chatbot') }}" class="btn-ask-omah">
                    <span>Mulai Bertanya</span>
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>

        </section>


        <!-- FOOTER -->
        <footer class="user-footer">

            <div class="user-footer-main">

                <div class="footer-brand">

                    <a href="{{ url('/user/dashboard') }}" class="footer-brand-logo">

                        <span class="footer-brand-icon">
                            <i class="bi bi-house-heart-fill"></i>
                        </span>

                        <span>Omah <strong>Amah</strong></span>

                    </a>

                    <p class="footer-description">
                        Temukan tempat tinggal yang nyaman dan sesuai dengan
                        kebutuhanmu. Omah Amah hadir untuk membantu perjalananmu
                        menemukan kos menjadi lebih mudah dan praktis.
                    </p>

                </div>


                <div class="footer-navigation">

                    <h4>Navigasi</h4>

                    <a href="{{ url('/user/dashboard') }}">Dashboard</a>

                    <a href="{{ route('user.inform') }}">Informasi Kost</a>

                    <a href="{{ url('/user/rooms') }}">Kamar &amp; Fasilitas</a>

                    <a href="{{ url('/user/chatbot') }}">Chatbot AI</a>

                    <a href="{{ route('user.profile') }}">
                        <i class="bi bi-person-circle"></i>
                        Profil Saya
                    </a>

                </div>

            </div>


            <div class="user-footer-bottom">

                <p>
                    &copy; {{ date('Y') }} Omah Amah.
                    Seluruh hak cipta dilindungi.
                </p>

                <span>
                    Dibuat dengan
                    <i class="bi bi-heart-fill"></i>
                    untuk kenyamananmu
                </span>

            </div>

        </footer>

    </main>


    <!-- JAVASCRIPT DASHBOARD -->
    <script src="{{ asset('js/user/dashboard.js') }}" defer></script>


    <!-- JAVASCRIPT DATA API -->
    <script>

        document.addEventListener('DOMContentLoaded', function() {

            // =========================================================
            // HELPER FUNCTIONS
            // =========================================================

            function formatRupiah(value) {
                const num = Number(value);
                if (isNaN(num) || num <= 0) return null;
                return 'Rp ' + num.toLocaleString('id-ID');
            }

            function setText(id, text) {
                const el = document.getElementById(id);
                if (el) el.textContent = text;
            }

            function setImage(id, url) {
                const el = document.getElementById(id);
                if (el && url) el.src = url;
            }

            // =========================================================
            // 1. DATA PROFIL KOST (dari tabel kost_profiles)
            // =========================================================

            fetch('/api/kost-profile', {
                headers: { 'Accept': 'application/json' }
            })
                .then(res => res.json())
                .then(res => {

                    if (res.success && res.data) {

                        const data = res.data;

                        // Lokasi: nama kost + alamat lengkap
                        setText('infoLokasi', data.name || 'Omah Amah');
                        setText('infoLokasiStatus', data.address || 'Alamat lengkap belum tersedia');

                    } else {
                        setText('infoLokasi', 'Omah Amah');
                        setText('infoLokasiStatus', 'Alamat belum tersedia');
                    }

                })
                .catch(err => {
                    console.error('Gagal memuat profil kost:', err);
                    setText('infoLokasi', 'Omah Amah');
                    setText('infoLokasiStatus', 'Gagal memuat data');
                });


            // =========================================================
            // 2. DATA KAMAR (dari tabel rooms)
            // =========================================================

            fetch('/api/rooms', {
                headers: { 'Accept': 'application/json' }
            })
                .then(res => res.json())
                .then(res => {

                    if (!res.success || !Array.isArray(res.data) || res.data.length === 0) {

                        setText('infoHarga', 'Hubungi Pengelola');
                        setText('infoHargaStatus', 'Harga menunggu konfirmasi');
                        setText('infoKetersediaan', '0 Kamar');
                        setText('infoKetersediaanStatus', 'Belum ada data');
                        return;

                    }

                    const rooms = res.data;

                    // ---- HARGA TERMURAH (dari semua kamar berharga > 0 & aktif) ----
                    const prices = rooms
                        .filter(r => r.is_active !== false && Number(r.monthly_price) > 0)
                        .map(r => Number(r.monthly_price));

                    if (prices.length > 0) {
                        const minPrice = Math.min(...prices);
                        setText('infoHarga', formatRupiah(minPrice));
                        setText('infoHargaStatus', 'Harga sewa mulai dari');
                    } else {
                        setText('infoHarga', 'Hubungi Pengelola');
                        setText('infoHargaStatus', 'Harga menunggu konfirmasi');
                    }

                    // ---- KETERSEDIAAN KAMAR (status 'tersedia') ----
                    const availableCount = rooms.filter(
                        r => r.availability === 'tersedia' && r.is_active !== false
                    ).length;

                    setText('infoKetersediaan', `${availableCount} Kamar`);
                    setText('infoKetersediaanStatus', availableCount > 0 ? 'Siap dihuni' : 'Belum ada kamar tersedia');

                    // ---- CARI KAMAR BERDASARKAN SLUG (sesuai DB) ----
                    // DB: slug 'standard', 'nyaman', 'privat'
                    const roomStandard = rooms.find(r => r.slug === 'standard');
                    const roomNyaman   = rooms.find(r => r.slug === 'nyaman');
                    const roomPrivat   = rooms.find(r => r.slug === 'privat');

                    // ---- KAMAR A (STANDARD) ----
                    if (roomStandard) {
                        const hargaA = formatRupiah(roomStandard.monthly_price);
                        setText('hargaKamarA', hargaA || 'Hubungi Pengelola');
                        setText('namaKamarA', roomStandard.name || 'Kamar Standard');
                        if (roomStandard.description) {
                            setText('descKamarA', roomStandard.description);
                        }
                        if (roomStandard.image_url) {
                            setImage('imgKamarA', roomStandard.image_url);
                        }
                    } else {
                        setText('hargaKamarA', 'Hubungi Pengelola');
                    }

                    // ---- KAMAR B (NYAMAN) ----
                    if (roomNyaman) {
                        const hargaB = formatRupiah(roomNyaman.monthly_price);
                        setText('hargaKamarB', hargaB || 'Hubungi Pengelola');
                        setText('namaKamarB', roomNyaman.name || 'Kamar Nyaman');
                        if (roomNyaman.description) {
                            setText('descKamarB', roomNyaman.description);
                        }
                        if (roomNyaman.image_url) {
                            setImage('imgKamarB', roomNyaman.image_url);
                        }
                    } else {
                        setText('hargaKamarB', 'Hubungi Pengelola');
                    }

                    // ---- KAMAR C (PRIVAT) ----
                    if (roomPrivat) {
                        const hargaC = formatRupiah(roomPrivat.monthly_price);
                        setText('hargaKamarC', hargaC || 'Hubungi Pengelola');
                        setText('namaKamarC', roomPrivat.name || 'Kamar Privat');
                        if (roomPrivat.description) {
                            setText('descKamarC', roomPrivat.description);
                        }
                        if (roomPrivat.image_url) {
                            setImage('imgKamarC', roomPrivat.image_url);
                        }
                    } else {
                        setText('hargaKamarC', 'Hubungi Pengelola');
                    }

                })
                .catch(err => {
                    console.error('Gagal memuat data kamar:', err);
                    setText('infoHarga', 'Gagal memuat');
                    setText('infoKetersediaan', 'Gagal memuat');
                });


            // =========================================================
            // 3. DATA FASILITAS (dari tabel facilities)
            // =========================================================

            fetch('/api/facilities', {
                headers: { 'Accept': 'application/json' }
            })
                .then(res => res.json())
                .then(res => {

                    if (res.success && Array.isArray(res.data)) {

                        // Hitung hanya fasilitas aktif
                        const activeFacilities = res.data.filter(f => f.is_active !== false);
                        const total = activeFacilities.length;

                        setText('infoFasilitas', `${total} Fasilitas`);
                        setText('infoFasilitasStatus',
                            total > 0 ? 'Tersedia untuk penghuni' : 'Belum ada fasilitas'
                        );

                    } else {
                        setText('infoFasilitas', '0 Fasilitas');
                        setText('infoFasilitasStatus', 'Data fasilitas tidak tersedia');
                    }

                })
                .catch(err => {
                    console.error('Gagal memuat fasilitas:', err);
                    setText('infoFasilitas', 'Gagal memuat');
                    setText('infoFasilitasStatus', '-');
                });

        });

    </script>

</body>
</html>