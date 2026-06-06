<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'AeroStock') — Inventori & Kasir</title>
  <meta name="description" content="AeroStock — Sistem Manajemen Inventori & Point of Sales">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@php($user = auth()->user())
<div class="app-layout">

  {{-- ── Sidebar ─────────────────────────────────────────────── --}}
  <aside class="sidebar">
    <div class="sidebar-logo">
      <img src="{{ asset('logo.png') }}" alt="AeroStock Logo">
    </div>

    <nav class="sidebar-nav">
      <div class="nav-section-label">Menu Utama</div>

      <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
            <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
          </svg>
        </span>
        <span>Dasbor</span>
      </a>

      <a href="{{ route('pos.index') }}" class="nav-item {{ request()->routeIs('pos.*') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
          </svg>
        </span>
        <span>POS</span>
      </a>

      <div class="nav-section-label" style="margin-top:8px">Manajemen</div>

      @can('manage-catalog')
      <a href="{{ route('inventory.index') }}" class="nav-item {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
          </svg>
        </span>
        <span>Inventori</span>
      </a>

      <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
            <line x1="7" y1="7" x2="7.01" y2="7"/>
          </svg>
        </span>
        <span>Produk</span>
      </a>

      <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
            <rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
          </svg>
        </span>
        <span>Kategori</span>
      </a>

      <a href="{{ route('suppliers.index') }}" class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/>
            <path d="M9 9h.01M9 13h.01M9 17h.01"/>
          </svg>
        </span>
        <span>Supplier</span>
      </a>
      @endcan

      <div class="nav-section-label" style="margin-top:8px">Sistem</div>

      <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
            <line x1="6" y1="20" x2="6" y2="14"/>
          </svg>
        </span>
        <span>Laporan</span>
      </a>

    </nav>

    <div class="sidebar-footer">
      <div class="user-card">
        <div class="user-avatar">{{ strtoupper(substr($user?->name ?? 'A', 0, 1)) }}</div>
        <div class="user-info">
          <div class="user-name">{{ $user?->name ?? 'Admin' }}</div>
          <div class="user-role">{{ $user?->role === 'admin' ? 'Admin' : 'Kasir' }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="btn btn-icon" title="Keluar" type="submit">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
              <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
          </button>
        </form>
      </div>
    </div>
  </aside>
  <button class="admin-sidebar-backdrop" type="button" onclick="closeAdminSidebar()" aria-label="Tutup menu"></button>

  {{-- ── Main Content ──────────────────────────────────────────── --}}
  <div class="main-content">

    {{-- Topbar --}}
    <header class="topbar">
      <button class="mobile-menu-btn" type="button" onclick="openAdminSidebar()" aria-label="Buka menu">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="3" y1="6" x2="21" y2="6"/>
          <line x1="3" y1="12" x2="21" y2="12"/>
          <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
      </button>
      <div class="topbar-left">
        <div class="breadcrumb">
          <span>AeroStock</span>
          <span>/</span>
          <span>@yield('breadcrumb', 'Dasbor')</span>
        </div>
        <h1 class="page-title">@yield('page-title', 'Dasbor')</h1>
      </div>
      <div class="topbar-right">
        <div class="search-bar">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
          </svg>
          <input type="text" placeholder="Cari...">
        </div>
        <div class="notification-menu">
          <button class="icon-btn" type="button" title="Notifikasi" aria-label="Notifikasi" aria-expanded="false" onclick="toggleNotifications(event)">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            @if(($topbarNotificationCount ?? 0) > 0)
              <span class="badge-dot"></span>
            @endif
          </button>

          <div class="notification-panel" id="notificationPanel">
            <div class="notification-header">
              <div>
                <div class="notification-title">Notifikasi</div>
                <div class="notification-subtitle">{{ ($topbarNotificationCount ?? 0) }} pembaruan toko</div>
              </div>
            </div>

            <div class="notification-list">
              @forelse(($topbarNotifications ?? collect()) as $notification)
                <a href="{{ $notification['url'] }}" class="notification-item {{ $notification['type'] }}">
                  <span class="notification-marker"></span>
                  <span class="notification-content">
                    <span class="notification-item-title">{{ $notification['title'] }}</span>
                    <span class="notification-message">{{ $notification['message'] }}</span>
                    <span class="notification-time">{{ $notification['time'] }}</span>
                  </span>
                </a>
              @empty
                <div class="notification-empty">Belum ada notifikasi baru.</div>
              @endforelse
            </div>

            <div class="notification-footer">
              @can('manage-catalog')
                <a href="{{ route('inventory.index') }}">Cek Inventori</a>
              @else
                <a href="{{ route('pos.index') }}">Buka Kasir</a>
              @endcan
              <a href="{{ route('reports.index') }}">Lihat Laporan</a>
            </div>
          </div>
        </div>
        <div style="font-size:12px;color:var(--slate-400)">
          {{ now()->locale('id')->isoFormat('ddd, D MMM') }}
        </div>
      </div>
    </header>

    {{-- Page Content --}}
    <main>
      @if(session('success'))
        <div style="padding:16px 24px 0">
          <div class="flash-msg success">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            {{ session('success') }}
          </div>
        </div>
      @endif
      @if(session('error'))
        <div style="padding:16px 24px 0">
          <div class="flash-msg error">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ session('error') }}
          </div>
        </div>
      @endif

      @yield('content')
    </main>

  </div>{{-- /.main-content --}}
</div>{{-- /.app-layout --}}

<nav class="admin-mobile-bottom-nav" aria-label="Navigasi mobile admin">
  <a href="{{ route('dashboard') }}" class="admin-mobile-tab {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
      <rect x="3" y="3" width="7" height="7" rx="1.4"/><rect x="14" y="3" width="7" height="7" rx="1.4"/>
      <rect x="3" y="14" width="7" height="7" rx="1.4"/><rect x="14" y="14" width="7" height="7" rx="1.4"/>
    </svg>
    <span>Dasbor</span>
  </a>

  @can('manage-catalog')
  <a href="{{ route('inventory.index') }}" class="admin-mobile-tab {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
      <path d="M21 16V8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
      <path d="M3.3 7 12 12l8.7-5"/><path d="M12 22V12"/>
    </svg>
    <span>Inventori</span>
  </a>

  <a href="{{ route('products.index') }}" class="admin-mobile-tab {{ request()->routeIs('products.*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
      <path d="M20.6 13.4l-7.2 7.2a2 2 0 0 1-2.8 0L2 12V2h10l8.6 8.6a2 2 0 0 1 0 2.8z"/>
      <path d="M7 7h.01"/>
    </svg>
    <span>Produk</span>
  </a>

  <a href="{{ route('categories.index') }}" class="admin-mobile-tab {{ request()->routeIs('categories.*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
      <rect x="3" y="3" width="7" height="7" rx="1.4"/><rect x="14" y="3" width="7" height="7" rx="1.4"/>
      <rect x="14" y="14" width="7" height="7" rx="1.4"/><rect x="3" y="14" width="7" height="7" rx="1.4"/>
    </svg>
    <span>Kategori</span>
  </a>

  <a href="{{ route('suppliers.index') }}" class="admin-mobile-tab {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
      <path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/>
      <path d="M9 9h.01M9 13h.01M9 17h.01"/>
    </svg>
    <span>Supplier</span>
  </a>
  @endcan

  <a href="{{ route('reports.index') }}" class="admin-mobile-tab {{ request()->routeIs('reports.*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
      <path d="M4 19V5"/><path d="M20 19H4"/>
      <rect x="7" y="11" width="3" height="5" rx="1"/><rect x="12" y="7" width="3" height="9" rx="1"/><rect x="17" y="9" width="3" height="7" rx="1"/>
    </svg>
    <span>Laporan</span>
  </a>

</nav>

@stack('modals')
@stack('scripts')
<script>
  function toggleNotifications(event) {
    event.stopPropagation();
    const menu = event.currentTarget.closest('.notification-menu');
    const isOpen = menu.classList.toggle('active');
    event.currentTarget.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  }

  document.addEventListener('click', () => {
    document.querySelectorAll('.notification-menu.active').forEach((menu) => {
      menu.classList.remove('active');
      menu.querySelector('.icon-btn')?.setAttribute('aria-expanded', 'false');
    });
  });

  document.getElementById('notificationPanel')?.addEventListener('click', (event) => {
    event.stopPropagation();
  });

  function openAdminSidebar() {
    document.body.classList.add('admin-sidebar-open');
  }

  function closeAdminSidebar() {
    document.body.classList.remove('admin-sidebar-open');
  }

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') closeAdminSidebar();
  });
</script>
</body>
</html>
