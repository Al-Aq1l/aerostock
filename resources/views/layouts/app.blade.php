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
        <span>Kasir (POS)</span>
      </a>

      <div class="nav-section-label" style="margin-top:8px">Manajemen</div>

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

      <a href="#" class="nav-item">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.07 4.93l-1.41 1.41M22 12h-2M19.07 19.07l-1.41-1.41M12 22v-2M4.93 19.07l1.41-1.41M2 12h2M4.93 4.93l1.41 1.41"/>
          </svg>
        </span>
        <span>Pengaturan</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="user-card">
        <div class="user-avatar">A</div>
        <div class="user-info">
          <div class="user-name">Admin</div>
          <div class="user-role">Super Admin</div>
        </div>
      </div>
    </div>
  </aside>

  {{-- ── Main Content ──────────────────────────────────────────── --}}
  <div class="main-content">

    {{-- Topbar --}}
    <header class="topbar">
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
        <button class="icon-btn" title="Notifikasi">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
          </svg>
          <span class="badge-dot"></span>
        </button>
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

@stack('modals')
@stack('scripts')
</body>
</html>
