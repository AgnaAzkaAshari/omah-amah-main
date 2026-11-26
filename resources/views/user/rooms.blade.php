<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kamar dan Fasilitas | Omah Amah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/user/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/rooms.css') }}">
</head>
<body>

    @include('user_partials.sidebar')

    <main class="main-content">

        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button
                    type="button"
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
                    <h1>Kamar dan Fasilitas</h1>
                    <p>Informasi pilihan kamar Omah Amah.</p>
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

        <div class="rooms-page-content">

            <!-- Hero Heading -->
            <section class="rooms-hero">
                <span class="rooms-eyebrow">TEMUKAN RUANG YANG SESUAI</span>
                <h2>Kamar dan Fasilitas</h2>
                <p>
                    Temukan pilihan kamar yang nyaman untuk mendukung
                    aktivitas belajar, bekerja, dan beristirahat.
                    Kenali fasilitas yang tersedia sebelum menentukan pilihan.
                </p>
            </section>

            <!-- Pilihan Kamar -->
            <section class="room-options-section">

                <div class="room-section-heading">
                    <div>
                        <h3>Pilihan Kamar</h3>
                        <p>
                            Jelajahi pilihan kamar dan temukan ruang
                            yang sesuai dengan kebutuhanmu.
                        </p>
                    </div>

                    <span class="room-count">
                        <i class="bi bi-house-door"></i>
                        {{ $rooms->count() }} Tipe Kamar
                    </span>
                </div>

                <div class="room-cards-grid">

                    @forelse($rooms as $room)
                        @php
                            // Ambil fasilitas kamar (maks 3 untuk card)
                            $roomFacilities = $room->facilities
                                ->where('category', 'kamar')
                                ->take(3);

                            // Icon map berdasarkan nama fasilitas
                            $iconMap = [
                                'AC'                => 'bi-snow',
                                'Meja Belajar'      => 'bi-laptop',
                                'Lemari'            => 'bi-door-closed',
                                'Kamar Mandi'       => 'bi-droplet',
                                'Kamar Mandi Dalam' => 'bi-droplet-half',
                                'Kamar Mandi Bersama' => 'bi-droplet',
                                'Kasur'             => 'bi-moon-stars',
                                'Wi-Fi'             => 'bi-wifi',
                                'Kulkas Bersama'    => 'bi-snow2',
                                'Dapur Bersama'     => 'bi-cup-hot',
                            ];

                            // Label status dari enum availability
                            $statusLabel = match($room->availability) {
                                'tersedia'         => 'Tersedia',
                                'penuh'            => 'Penuh',
                                'perlu_konfirmasi' => 'Perlu Konfirmasi',
                                default            => 'Pilihan kamar',
                            };
                            $statusClass = $room->availability === 'tersedia' ? 'is-available' : '';
                        @endphp

                        <article class="room-card">

                            <div class="room-image-wrapper">
                                <img
                                    src="{{ $room->image_url ?? 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=900&q=85' }}"
                                    alt="Kamar {{ $room->name }} di Omah Amah"
                                    class="room-image"
                                    loading="lazy">
                            </div>

                            <div class="room-card-body">
                                <div class="room-title-row">
                                    <h4>{{ $room->name }}</h4>
                                    <span class="room-status {{ $statusClass }}">
                                        <span></span> {{ $statusLabel }}
                                    </span>
                                </div>

                                <div class="room-price">
                                    @if($room->monthly_price)
                                        <strong>Rp{{ number_format($room->monthly_price, 0, ',', '.') }}</strong>
                                        <small>/ bulan</small>
                                    @else
                                        <strong>Hubungi pengelola</strong>
                                    @endif
                                </div>

                                <p class="room-description">
                                    {{ $room->description ?? 'Kamar nyaman untuk beristirahat dan menjalani aktivitas sehari-hari.' }}
                                </p>

                                <div class="room-facilities">
                                    @forelse($roomFacilities as $facility)
                                        <span>
                                            <i class="bi {{ $iconMap[$facility->name] ?? 'bi-check-circle' }}"></i>
                                            {{ $facility->name }}
                                        </span>
                                    @empty
                                        <span>
                                            <i class="bi bi-info-circle"></i>
                                            Fasilitas menyusul
                                        </span>
                                    @endforelse
                                </div>

                                <a href="{{ route('user.rooms.show', $room->slug) }}"
                                   class="room-detail-button">
                                    Lihat Detail
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                        </article>
                    @empty
                        <div class="room-card" style="grid-column: 1 / -1; padding: 2rem; text-align: center;">
                            <p style="margin: 0; color: #6b7280;">
                                <i class="bi bi-info-circle"></i>
                                Belum ada kamar yang tersedia saat ini.
                                Silakan hubungi pengelola Omah Amah.
                            </p>
                        </div>
                    @endforelse

                </div>

                <div class="room-disclaimer">
                    <i class="bi bi-info-circle"></i>
                    <p>
                        Foto kamar merupakan ilustrasi referensi desain.
                        Tipe, harga, fasilitas, dan ketersediaan kamar
                        perlu dikonfirmasi langsung kepada pengelola Omah Amah.
                    </p>
                </div>

            </section>


<!-- Section Fasilitas -->
<section class="room-facilities-section">

    <div class="room-facilities-heading">
        <span class="room-facilities-eyebrow">FASILITAS OMAH AMAH</span>
        <h2>Fasilitas Sesuai Kebutuhanmu</h2>
        <p>
            Kenali fasilitas kamar, fasilitas bersama, dan layanan
            tambahan yang dapat menunjang kenyamanan selama tinggal
            di Omah Amah.
        </p>
    </div>

    @php
        // Group fasilitas berdasarkan kategori dari DB
        $facilitiesByCategory = $facilities->groupBy('category');

        // Map kategori → judul, ikon, class, deskripsi
        $categoryMeta = [
            'kamar' => [
                'title'       => 'Fasilitas Kamar',
                'icon'        => 'bi-house-door-fill',
                'icon_class'  => 'icon-bedroom',
                'description' => 'Fasilitas untuk mendukung aktivitas pribadi, belajar, dan beristirahat.',
                'list_icon'   => 'bi-check-circle',
            ],
            'bersama' => [
                'title'       => 'Fasilitas Bersama',
                'icon'        => 'bi-people-fill',
                'icon_class'  => 'icon-shared',
                'description' => 'Fasilitas umum untuk menunjang kebutuhan dan aktivitas sehari-hari penghuni.',
                'list_icon'   => 'bi-check-circle',
            ],
            'layanan' => [
                'title'       => 'Layanan Tambahan',
                'icon'        => 'bi-stars',
                'icon_class'  => 'icon-extra',
                'description' => 'Layanan pendukung untuk membantu memenuhi kebutuhan penghuni selama tinggal di kost.',
                'list_icon'   => 'bi-basket2-fill',
            ],
        ];

        // Icon per nama fasilitas untuk list
        $facilityIconMap = [
            'AC'                  => 'bi-snow',
            'Meja Belajar'        => 'bi-laptop',
            'Lemari'              => 'bi-door-closed-fill',
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
    @endphp

    <div class="room-facilities-cards">

        @foreach($categoryMeta as $categoryKey => $meta)
            @php
                $items = $facilitiesByCategory->get($categoryKey, collect());
            @endphp

            @if($items->isNotEmpty())
                <article class="room-facility-category">

                    <div class="room-facility-category-icon {{ $meta['icon_class'] }}">
                        <i class="bi {{ $meta['icon'] }}"></i>
                    </div>

                    <h3>{{ $meta['title'] }}</h3>

                    <p class="room-facility-category-description">
                        {{ $meta['description'] }}
                    </p>

                    <div class="room-facility-list">
                        @foreach($items as $facility)
                            <div class="room-facility-list-item">
                                <span class="room-facility-list-icon">
                                    <i class="bi {{ $facilityIconMap[$facility->name] ?? $meta['list_icon'] }}"></i>
                                </span>
                                <span>{{ $facility->name }}</span>
                            </div>
                        @endforeach
                    </div>

                </article>
            @endif
        @endforeach

    </div>

    <div class="room-facilities-note">
        <i class="bi bi-info-circle"></i>
        <p>
            Ketersediaan fasilitas dapat berbeda sesuai tipe kamar
            dan ketentuan pengelola Omah Amah.
        </p>
    </div>

</section>


<!-- CTA Tanya Amah AI -->
<section class="rooms-ai-cta">
    <div class="rooms-ai-cta-content">
        <span class="rooms-ai-cta-eyebrow">
            <i class="bi bi-stars"></i>
            BUTUH BANTUAN?
        </span>

        <h2>Masih bingung memilih kamar?</h2>

        <p>
            Tenang, Amah AI siap membantu menjawab pertanyaanmu
            dan memberikan informasi untuk membantumu memilih
            kamar yang sesuai dengan kebutuhan.
        </p>

        <a href="{{ url('/user/chatbot') }}" class="rooms-ai-cta-button">
            <i class="bi bi-robot"></i>
            Tanya Amah AI
            <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="rooms-ai-cta-decoration">
        <div class="rooms-ai-cta-icon">
            <i class="bi bi-chat-heart-fill"></i>
        </div>
        <span class="rooms-ai-cta-sparkle sparkle-one">
            <i class="bi bi-stars"></i>
        </span>
        <span class="rooms-ai-cta-sparkle sparkle-two">
            <i class="bi bi-plus-lg"></i>
        </span>
    </div>
</section>

<!-- Footer Omah Amah -->
<footer class="user-footer">

    <div class="user-footer-main">

        <!-- Identitas dan Deskripsi -->
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

        <!-- Navigasi -->
        <div class="footer-navigation">
            <h4>Navigasi</h4>

            <a href="{{ url('/user/dashboard') }}">Dashboard</a>
            <a href="{{ route('user.inform') }}">Informasi Kost</a>
            <a href="{{ url('/rooms') }}">Kamar &amp; Fasilitas</a>
            <a href="{{ url('/chatbot') }}">Chatbot AI</a>
        </div>

    </div>

    <!-- Copyright -->
    <div class="user-footer-bottom">
        <p>
            &copy; {{ date('Y') }} Omah Amah.
            Seluruh hak cipta dilindungi.
        </p>

        <span>
            Dibuat dengan <i class="bi bi-heart-fill"></i> untuk kenyamananmu
        </span>
    </div>

</footer>



        </div>

        

    </main>

    <script src="{{ asset('js/user/dashboard.js') }}" defer></script>

</body>
</html>