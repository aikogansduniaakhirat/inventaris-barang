<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Inventaris Barang Sekolah</title>
    <meta name="description" content="Sistem Informasi Inventaris Barang Sekolah — Kelola aset sekolah dengan mudah dan efisien.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

<!-- ═══════════════════════════════════════════════════════════════ SIDEBAR -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="bi bi-box-seam-fill"></i>
        </div>
        <div class="sidebar-brand-text">
            <span class="brand-name">Inventaris</span>
            <span class="brand-sub">Barang Sekolah</span>
        </div>
    </div>

    <div class="sidebar-divider"></div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">MENU UTAMA</div>

        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('barang.index') }}" class="nav-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
            <i class="bi bi-boxes"></i>
            <span>Data Barang</span>
        </a>

        <a href="{{ route('peminjaman.index') }}" class="nav-item {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right"></i>
            <span>Peminjaman</span>
            @php
                $tlb = \App\Models\Peminjaman::where('status','terlambat')->count();
                $mtg = \App\Models\Peminjaman::where('status','menunggu')->count();
            @endphp
            <span class="ms-auto d-flex gap-1">
                @if($mtg > 0 && auth()->user()->isAdmin())
                    <span class="badge bg-warning text-dark rounded-pill">{{ $mtg }}</span>
                @endif
                @if($tlb > 0)
                    <span class="badge bg-danger rounded-pill">{{ $tlb }}</span>
                @endif
            </span>
        </a>

        @if(auth()->user()->isAdmin())
        <a href="{{ route('kategori.index') }}" class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            <i class="bi bi-tags-fill"></i>
            <span>Kategori</span>
        </a>
        @endif

        <div class="nav-section-title mt-2">LAPORAN</div>

        <a href="{{ route('laporan.stok-barang') }}" class="nav-item {{ request()->routeIs('laporan.stok*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i>
            <span>Laporan Stok</span>
        </a>

        <a href="{{ route('laporan.riwayat-peminjaman') }}" class="nav-item {{ request()->routeIs('laporan.riwayat*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Riwayat Peminjaman</span>
        </a>

        @if(auth()->user()->isAdmin())
        <div class="nav-section-title mt-2">ADMINISTRASI</div>

        <a href="{{ route('user.index') }}" class="nav-item {{ request()->routeIs('user.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Manajemen User</span>
        </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                {{ strtoupper(substr(auth()->user()->display_name, 0, 1)) }}
            </div>
            <div class="sidebar-user-info">
                <span class="user-name">{{ auth()->user()->display_name }}</span>
                <span class="user-role badge bg-{{ auth()->user()->role_badge }}">{{ auth()->user()->role_label }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ═══════════════════════════════════════════════════════════════ MAIN CONTENT -->
<div class="main-wrapper" id="mainWrapper">

    <!-- Topbar -->
    <header class="topbar">
        <button class="sidebar-toggle btn" id="sidebarToggle" title="Toggle Sidebar">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="topbar-breadcrumb">
            <h5 class="page-title mb-0">@yield('page-title', 'Dashboard')</h5>
        </div>

        <div class="topbar-actions ms-auto d-flex align-items-center gap-2">
            <!-- Notifikasi Terlambat -->
            @php $notifCount = \App\Models\Peminjaman::where('status','terlambat')->orWhere(fn($q)=>$q->where('status','dipinjam')->where('tanggal_kembali_rencana','<',now()))->count() @endphp
            @if($notifCount > 0)
            <a href="{{ route('peminjaman.index', ['status'=>'terlambat']) }}" class="btn btn-sm btn-outline-danger position-relative" title="{{ $notifCount }} peminjaman terlambat">
                <i class="bi bi-bell-fill"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $notifCount }}</span>
            </a>
            @endif

            <!-- User Dropdown -->
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->display_name, 0, 1)) }}</div>
                    <span class="d-none d-md-inline">{{ auth()->user()->display_name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
        <!-- Flash Messages -->
        @foreach(['success' => 'success', 'error' => 'danger', 'warning' => 'warning', 'info' => 'info'] as $type => $class)
            @if(session($type))
            <div class="alert alert-{{ $class }} alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-{{ $type === 'success' ? 'check-circle' : ($type === 'error' ? 'x-circle' : ($type === 'warning' ? 'exclamation-triangle' : 'info-circle')) }}-fill me-2"></i>
                {!! session($type) !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        @endforeach

        @yield('content')
    </main>
</div>

<!-- ═══════════════════════════════════════════════════════════════ SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<script>
const sidebar     = document.getElementById('sidebar');
const mainWrapper = document.getElementById('mainWrapper');
const toggle      = document.getElementById('sidebarToggle');
const overlay     = document.getElementById('sidebarOverlay');

const isMobile = () => window.innerWidth < 769;

// Restore desktop collapsed state
if (!isMobile() && localStorage.getItem('sidebarCollapsed') === 'true') {
    sidebar.classList.add('collapsed');
    mainWrapper.classList.add('sidebar-collapsed');
}

toggle?.addEventListener('click', () => {
    if (isMobile()) {
        // Mobile: slide in/out with overlay
        const isOpen = sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('active', isOpen);
        document.body.style.overflow = isOpen ? 'hidden' : '';
    } else {
        // Desktop: collapse/expand
        sidebar.classList.remove('mobile-open');
        sidebar.classList.toggle('collapsed');
        mainWrapper.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    }
});

// Close sidebar when clicking overlay
overlay?.addEventListener('click', closeMobileSidebar);

// Close on nav link click (mobile)
sidebar.querySelectorAll('.nav-item').forEach(link => {
    link.addEventListener('click', () => { if (isMobile()) closeMobileSidebar(); });
});

function closeMobileSidebar() {
    sidebar.classList.remove('mobile-open');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
}

// On resize: clean up mobile state if switching to desktop
window.addEventListener('resize', () => {
    if (!isMobile()) {
        closeMobileSidebar();
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            mainWrapper.classList.add('sidebar-collapsed');
        }
    } else {
        sidebar.classList.remove('collapsed');
        mainWrapper.classList.remove('sidebar-collapsed');
    }
});

// Auto-dismiss alerts after 5s
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
        bsAlert?.close();
    }, 5000);
});
</script>

@stack('scripts')
</body>
</html>
