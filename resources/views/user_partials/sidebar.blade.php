<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar" aria-label="Navigasi utama">
    <div class="brand">
        <span class="brand-icon">⌂</span>
        <span class="brand-text">Omah Amah</span>
    </div>

    <div class="menu-label">MENU UTAMA</div>

    <nav class="sidebar-nav">
        
        <a href="{{ route('user.dashboard') }}"
           class="menu-link sidebar-link {{ request()->is('user/dashboard') ? 'active' : '' }}"
           title="Dashboard">
            <i class="bi bi-grid menu-icon"></i>
            <span class="menu-text">Dashboard</span>
        </a>
        
        <a href="{{ route('user.inform') }}"
           class="menu-link sidebar-link {{ request()->is('user/informasi-kost') ? 'active' : '' }}"
           title="Informasi Kost">
            <i class="bi bi-info-circle menu-icon"></i>
            <span class="menu-text">Informasi Kost</span>
        </a>

        <a href="{{ route('user.rooms') }}"
           class="menu-link {{ request()->is('user/rooms') ? 'active' : '' }}"
           title="Kamar dan Fasilitas">
            <span class="menu-icon">
                <i class="bi bi-house-door"></i>
            </span>
            <span class="menu-text">Kamar dan Fasilitas</span>
        </a>

        <a href="{{ url('/user/chatbot') }}"
           class="menu-link {{ request()->is('user/chatbot') ? 'active' : '' }}"
           title="Chatbot">
            <span class="menu-icon">
                <i class="bi bi-robot"></i>
            </span>
            <span class="menu-text">Chatbot</span>
        </a>

        <!-- MENU PROFIL SAYA -->
        <a href="{{ route('user.profile') }}" 
           class="menu-link {{ request()->routeIs('user.profile*') ? 'active' : '' }}" 
           title="Profil Saya">
            <span class="menu-icon">
                <i class="bi bi-person"></i>
            </span>
            <span class="menu-text">Profil Saya</span>
        </a>

        <!-- MENU KELUAR (LOGOUT) -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" 
           class="menu-link" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           title="Keluar">
            <span class="menu-icon">
                <i class="bi bi-box-arrow-right"></i>
            </span>
            <span class="menu-text">Keluar</span>
        </a>

    </nav>
</aside>