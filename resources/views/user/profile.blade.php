<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil Saya | Omah Amah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #1B3D63;
            --primary-hover: #2f5a8a;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --bg-body: #f8fafc;
            --sidebar-w: 260px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
        }

        /* ===========================
           SIDEBAR KIRI
        =========================== */
        .sidebar {
            width: var(--sidebar-w);
            background: #ffffff;
            border-right: 1px solid var(--border-color);
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 100;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 800;
            color: var(--primary);
            padding: 0 12px 24px;
            text-decoration: none;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            background: var(--primary);
            color: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all .2s;
        }

        .nav-link:hover {
            background: #f1f5f9;
            color: var(--text-main);
        }

        .nav-link.active {
            background: var(--primary);
            color: #ffffff;
            font-weight: 600;
        }

        .nav-link i {
            font-size: 18px;
        }

        /* Kartu Promo di Sidebar */
        .promo-card {
            background: #eef4fa;
            border-radius: 12px;
            padding: 16px;
            margin-top: 32px;
        }

        .promo-card h6 {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .promo-card p {
            font-size: 11px;
            color: var(--text-muted);
            line-height: 1.5;
            margin: 0;
        }

        /* ===========================
           MAIN AREA
        =========================== */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Topbar */
        .topbar {
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 32px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .breadcrumb .active {
            color: var(--primary);
            font-weight: 700;
        }

        .user-profile-top {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 700;
            color: var(--primary);
        }

        .avatar-sm {
            width: 36px;
            height: 36px;
            background: #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: var(--primary);
            overflow: hidden;
        }

        /* Page Content */
        .page-content {
            padding: 32px;
            max-width: 1200px;
        }

        .page-title h2 {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 4px;
        }

        .page-title p {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        /* Grid Layout */
        .profile-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 24px;
            align-items: start;
        }

        /* Card Standard */
        .card-clean {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
        }

        /* Profil Kiri */
        .profile-center {
            text-align: center;
        }

        .avatar-lg {
            width: 90px;
            height: 90px;
            background: #eef4fa;
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            margin: 0 auto 16px;
        }

        .profile-center h4 {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        .profile-center p {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 16px;
        }

        .badge-verified {
            display: inline-block;
            background: #ecfdf5;
            color: #059669;
            font-size: 11px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            margin-bottom: 20px;
        }

        .btn-upload {
            display: block;
            width: 100%;
            padding: 10px;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-main);
            text-align: center;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-upload:hover { background: #f8fafc; }

        .help-text {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 8px;
            display: block;
        }

        /* Keamanan */
        .security-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .security-desc {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .security-pass {
            font-size: 20px;
            letter-spacing: 2px;
            color: var(--text-main);
            margin-bottom: 20px;
            line-height: 1;
        }

        .btn-sec {
            display: block;
            width: 100%;
            padding: 10px;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            color: var(--primary);
            text-align: center;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-sec:hover { background: #f8fafc; }

        /* Privacy */
        .privacy-card {
            background: #eef4fa;
            border: none;
        }

        .privacy-card h6 {
            font-size: 13px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .privacy-card p {
            font-size: 12px;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.5;
        }

        /* Form Kanan */
        .form-header {
            margin-bottom: 24px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
        }

        .form-header h3 {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 4px;
        }

        .form-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin: 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
        }

        .form-control {
            height: 44px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0 16px;
            font-size: 14px;
            color: var(--text-main);
            background: #fff;
            transition: border-color .2s;
        }

        textarea.form-control {
            height: 80px;
            padding: 12px 16px;
            resize: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(27, 61, 99, 0.1);
        }

        /* Footer Form */
        .form-footer {
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-footer small {
            font-size: 12px;
            color: #94a3b8;
        }

        .btn-actions {
            display: flex;
            gap: 12px;
        }

        .btn-cancel {
            padding: 10px 24px;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-muted);
            text-decoration: none;
            transition: all .2s;
        }

        .btn-cancel:hover { background: #f8fafc; }

        .btn-save {
            padding: 10px 24px;
            background: var(--primary);
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-save:hover { background: var(--primary-hover); }

        /* Modal Password */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.4);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            background: #fff;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            padding: 28px;
            box-shadow: 0 20px 40px rgba(0,0,0,.1);
        }

        .modal-box h4 {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 6px;
        }

        .modal-box p {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        .modal-box .form-group { margin-bottom: 16px; }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        /* Mobile Toggle */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--primary);
            cursor: pointer;
        }

        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .3s;
            }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .profile-grid { grid-template-columns: 1fr; }
            .menu-toggle { display: block; }
            .topbar { padding: 0 20px; }
            .page-content { padding: 20px; }
        }

        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-footer { flex-direction: column; align-items: flex-start; gap: 16px; }
        }
    </style>
</head>
<body>

@php
    $user = auth()->user();
    $initial = strtoupper(substr($user->name ?? 'U', 0, 1));
    // Cek foto dari kolom 'photo' atau 'profile_photo'
    $userPhoto = $user->photo ?? $user->profile_photo ?? null;
@endphp

<!-- ===========================
     SIDEBAR
=========================== -->
<aside class="sidebar" id="sidebar">
    <a href="{{ route('user.dashboard') }}" class="brand">
        <div class="brand-icon"><i class="bi bi-house-door-fill"></i></div>
        Omah Amah
    </a>

    <ul class="nav-list">
        <li>
            <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('user.inform') }}" class="nav-link {{ request()->routeIs('user.inform') ? 'active' : '' }}">
                <i class="bi bi-house"></i> Informasi Kost
            </a>
        </li>
        <li>
            <a href="{{ route('user.rooms') }}" class="nav-link {{ request()->routeIs('user.rooms*') ? 'active' : '' }}">
                <i class="bi bi-door-open"></i> Kamar & Fasilitas
            </a>
        </li>
        <li>
            <a href="{{ route('user.chatbot') }}" class="nav-link {{ request()->routeIs('user.chatbot') ? 'active' : '' }}">
                <i class="bi bi-chat-dots"></i> Chatbot AI
            </a>
        </li>
        <li>
            <a href="{{ route('user.profile') }}" class="nav-link {{ request()->routeIs('user.profile*') ? 'active' : '' }}">
                <i class="bi bi-person"></i> Profil Saya
            </a>
        </li>
        
        <!-- TAMBAHAN: MENU KELUAR -->
        <li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i> Keluar
            </a>
        </li>
    </ul>

    <!-- Promo Card -->
    <div class="promo-card">
        <h6><i class="bi bi-stars"></i> Cari tempat yang terasa seperti rumah.</h6>
        <p>Mulai dari kamar, lalu kenali informasi kos bersama Amah AI.</p>
    </div>
</aside>

<!-- ===========================
     MAIN CONTENT
=========================== -->
<main class="main-content">

    <!-- TOPBAR -->
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <i class="bi bi-list"></i>
            </button>
            <div class="breadcrumb">
                <i class="bi bi-house"></i> Dashboard
                <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                <span class="active">Profil Saya</span>
            </div>
        </div>

        <div class="user-profile-top">
            <div class="avatar-sm">
                @if($userPhoto)
                    <img src="{{ asset('storage/'.$userPhoto) }}" alt="Foto" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ $initial }}
                @endif
            </div>
            {{ $user->name ?? 'Pengguna' }}
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <div class="page-content">

        <div class="page-title">
            <h2>Profil Saya</h2>
            <p>Kelola informasi pribadi dan keamanan akunmu di satu tempat.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="border-radius:8px; font-size:14px;">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" style="border-radius:8px; font-size:14px;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-grid">

            <!-- KOLOM KIRI -->
            <div>
                <!-- Foto Profil -->
                <div class="card-clean">
                    <div class="profile-center">
                        @if($userPhoto)
                            <img id="avatarPreview" src="{{ asset('storage/'.$userPhoto) }}" alt="Foto" style="width:90px;height:90px;border-radius:50%;object-fit:cover;margin:0 auto 16px;display:block;">
                            <div id="avatarDefault" class="avatar-lg" style="display:none;">{{ $initial }}</div>
                        @else
                            <div id="avatarDefault" class="avatar-lg">{{ $initial }}</div>
                            <img id="avatarPreview" src="" alt="Foto" style="width:90px;height:90px;border-radius:50%;object-fit:cover;margin:0 auto 16px;display:none;">
                        @endif

                        <h4>{{ $user->name }}</h4>
                        <p>{{ $user->email }}</p>
                        <span class="badge-verified">Email terverifikasi</span>

                        <label for="profilePhoto" class="btn-upload">
                            Ubah foto profil
                        </label>
                        <!-- name disesuaikan dengan database: profile_photo -->
                        <input type="file" id="profilePhoto" name="profile_photo" form="profileForm" accept="image/jpeg,image/png" hidden>
                        <span class="help-text">JPG atau PNG. Maksimal 2 MB.</span>
                    </div>
                </div>

                <!-- Keamanan Akun -->
                <div class="card-clean">
                    <div class="security-title">Keamanan akun</div>
                    <div class="security-desc">Jaga akunmu tetap aman dengan kata sandi yang kuat.</div>
                    <div style="font-size:12px;font-weight:600;color:var(--text-muted);margin-bottom:8px;">Kata sandi</div>
                    <div class="security-pass">••••••••••</div>
                    <button type="button" class="btn-sec" onclick="openPasswordModal()">
                        Ubah kata sandi
                    </button>
                </div>

                <!-- Privasi -->
                <div class="card-clean privacy-card">
                    <h6><i class="bi bi-shield-lock"></i> Privasimu penting</h6>
                    <p>Informasi pribadimu tidak ditampilkan kepada pengguna lain.</p>
                </div>
            </div>

            <!-- KOLOM KANAN (Form) -->
            <div>
                <div class="card-clean">
                    <div class="form-header">
                        <h3>Informasi Pribadi</h3>
                        <p>Pastikan data dirimu selalu terbaru.</p>
                    </div>

                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                        @csrf
                        @method('PATCH')

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nama lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label>Alamat email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="form-group">
                                <label>Nomor telepon</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            </div>

                            <div class="form-group">
                                <label>Jenis kelamin</label>
                                <select name="gender" class="form-control">
                                    <option value="">Pilih jenis kelamin</option>
                                    <option value="Laki-laki" {{ old('gender', $user->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('gender', $user->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Tanggal lahir</label>
                                <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $user->birth_date) }}">
                            </div>

                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <select name="occupation" class="form-control">
                                    <option value="">Pilih pekerjaan</option>
                                    @foreach(['Mahasiswa', 'Pelajar', 'Karyawan', 'Wiraswasta', 'Lainnya'] as $job)
                                        <option value="{{ $job }}" {{ old('occupation', $user->occupation) == $job ? 'selected' : '' }}>{{ $job }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- PERBAIKAN: name disesuaikan dengan kolom database "domicile_address" -->
                            <div class="form-group full">
                                <label>Alamat domisili</label>
                                <textarea name="domicile_address" class="form-control" placeholder="Masukkan alamat tempat tinggal saat ini">{{ old('domicile_address', $user->domicile_address) }}</textarea>
                                <span style="font-size:11px;color:var(--text-muted);">Gunakan alamat tempat tinggalmu saat ini.</span>
                            </div>
                        </div>

                        <div class="form-footer">
                            <small>Periksa kembali sebelum menyimpan.</small>
                            <div class="btn-actions">
                                <a href="{{ route('user.dashboard') }}" class="btn-cancel">Batal</a>
                                <button type="submit" class="btn-save">Simpan perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div style="font-size:12px; color:var(--text-muted); margin-top:12px;">
                    Butuh bantuan? Hubungi pengelola kos untuk informasi terkait hunianmu.
                </div>
            </div>

        </div>
    </div>
</main>

<!-- ===========================
     MODAL UBAH PASSWORD
=========================== -->
<div class="modal-overlay" id="passwordModal">
    <div class="modal-box">
        <h4>Ubah Kata Sandi</h4>
        <p>Masukkan kata sandi lama dan kata sandi baru Anda.</p>

        <form action="{{ route('user.profile.password') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label>Kata sandi lama</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Kata sandi baru</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required minlength="8">
            </div>

            <div class="form-group">
                <label>Konfirmasi kata sandi baru</label>
                <input type="password" name="password_confirmation" class="form-control" required minlength="8">
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closePasswordModal()">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview Foto
    document.getElementById('profilePhoto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto maksimal 2 MB.');
            this.value = '';
            return;
        }

        const preview = document.getElementById('avatarPreview');
        const def = document.getElementById('avatarDefault');

        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
        if (def) def.style.display = 'none';
    });

    // Modal Password
    function openPasswordModal() {
        document.getElementById('passwordModal').classList.add('active');
    }
    function closePasswordModal() {
        document.getElementById('passwordModal').classList.remove('active');
    }
    document.getElementById('passwordModal').addEventListener('click', function(e) {
        if (e.target === this) closePasswordModal();
    });
</script>

</body>
</html>