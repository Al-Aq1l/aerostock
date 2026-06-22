<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AeroStock — Sistem POS UMKM Retail</title>
  <meta name="description" content="AeroStock membantu UMKM retail mengelola kasir, stok, produk, supplier, dan laporan penjualan secara real-time.">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    h1, h2, h3, .font-display {
      font-family: 'Outfit', sans-serif;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-white to-slate-50 text-slate-800 min-h-screen overflow-x-hidden antialiased">
  
  <!-- BACKGROUND DECORATIONS (Radial Glows) -->
  <div class="absolute top-0 left-0 w-full h-[600px] overflow-hidden -z-10 pointer-events-none">
    <div class="absolute -top-[20%] -left-[10%] w-[50%] aspect-square rounded-full bg-blue-500/10 blur-[120px] animate-pulse duration-[8s]"></div>
    <div class="absolute top-[10%] -right-[15%] w-[45%] aspect-square rounded-full bg-emerald-500/80 blur-[150px] opacity-10 pointer-events-none"></div>
  </div>

  <!-- NAVIGATION BAR (Tailwind Layout & Hover States) -->
  <header class="sticky top-0 z-50 w-full border-b border-slate-200/60 bg-white/75 backdrop-blur-md transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 md:px-12 h-20 flex items-center justify-between">
      <a href="{{ route('landing') }}" class="flex items-center gap-2 transform hover:scale-[1.03] transition-transform duration-300">
        <img src="{{ asset('logo.png') }}" alt="AeroStock" class="w-[180px] h-auto">
      </a>
      
      <!-- NAV MENU (Desktop) -->
      <nav class="hidden md:flex items-center gap-1 bg-slate-100/80 p-1.5 rounded-full border border-slate-200/50">
        <a href="#fitur" class="px-5 py-2 text-sm font-semibold text-slate-600 rounded-full hover:text-blue-600 hover:bg-white/90 transition-all duration-300">Fitur</a>
        <a href="#alur" class="px-5 py-2 text-sm font-semibold text-slate-600 rounded-full hover:text-blue-600 hover:bg-white/90 transition-all duration-300">Alur Kerja</a>
        <a href="#kontak" class="px-5 py-2 text-sm font-semibold text-slate-600 rounded-full hover:text-blue-600 hover:bg-white/90 transition-all duration-300">Kontak</a>
      </nav>

      <!-- AUTH ACTIONS (Tailwind Hover, Active States) -->
      <div class="hidden md:flex items-center gap-3">
        @auth
          <a href="{{ auth()->user()->isAdmin() ? route('dashboard') : route('pos.index') }}" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold rounded-xl shadow-md shadow-blue-600/10 hover:shadow-blue-600/20 transition-all duration-300 text-sm">
            {{ auth()->user()->isAdmin() ? 'Buka Dasbor' : 'Buka POS' }}
          </a>
        @else
          <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Masuk</a>
          <a href="{{ route('register') }}" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold rounded-xl shadow-md shadow-blue-600/10 hover:shadow-blue-600/20 transition-all duration-300 text-sm">Daftar Sekarang</a>
        @endauth
      </div>

      <!-- MOBILE TOGGLE -->
      <button class="md:hidden p-2 rounded-xl border border-slate-200 bg-white shadow-sm" onclick="toggleLandingMenu(this)">
        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>

    <!-- MOBILE MENU LIST -->
    <div id="mobileMenu" class="hidden md:hidden absolute top-20 left-0 w-full bg-white border-b border-slate-200 px-6 py-6 shadow-xl flex-col gap-4 animate-in">
      <a href="#fitur" class="py-2 text-base font-semibold text-slate-600 border-b border-slate-100" onclick="closeMobileMenu()">Fitur</a>
      <a href="#alur" class="py-2 text-base font-semibold text-slate-600 border-b border-slate-100" onclick="closeMobileMenu()">Alur Kerja</a>
      <a href="#kontak" class="py-2 text-base font-semibold text-slate-600 border-b border-slate-100" onclick="closeMobileMenu()">Kontak</a>
      <div class="flex items-center gap-3 pt-2">
        @auth
          <a href="{{ auth()->user()->isAdmin() ? route('dashboard') : route('pos.index') }}" class="w-full text-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-lg">
            {{ auth()->user()->isAdmin() ? 'Buka Dasbor' : 'Buka POS' }}
          </a>
        @else
          <a href="{{ route('login') }}" class="w-1/2 text-center py-3 border border-slate-200 font-semibold rounded-xl text-slate-600">Masuk</a>
          <a href="{{ route('register') }}" class="w-1/2 text-center py-3 bg-blue-600 text-white font-semibold rounded-xl">Daftar</a>
        @endauth
      </div>
    </div>
  </header>

  <main class="overflow-x-hidden">
    <!-- HERO SECTION (Tailwind Grid & Animations) -->
    <section class="max-w-7xl mx-auto px-6 md:px-12 py-16 md:py-24 grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
      
      <!-- HERO TEXT -->
      <div class="lg:col-span-7 flex flex-col justify-center text-left reveal-item transform translate-y-10 opacity-0 transition-all duration-1000">
        <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 border border-blue-100 text-blue-600 text-xs font-extrabold uppercase tracking-wider rounded-full mb-6 w-max animate-pulse">
          POS & INVENTORI UMKM RETAIL
        </span>
        <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 leading-[1.08] tracking-tight mb-6">
          Kelola Transaksi Toko <br>
          <span class="text-blue-600 inline-block min-w-[280px]" id="morphingText">Lebih Cepat</span>
        </h1>
        <p class="text-base md:text-lg text-slate-500 leading-relaxed max-w-xl mb-8">
          Platform kasir dan stok terintegrasi yang membantu memantau transaksi penjualan, stok barang masuk-keluar, supplier, dan laporan keuangan secara real-time.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-4 mb-12">
          @auth
            <a href="{{ route('pos.index') }}" class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold rounded-2xl shadow-xl shadow-blue-600/20 hover:shadow-blue-600/30 transition-all duration-300 text-center flex items-center justify-center gap-2 group">
              Mulai Transaksi
              <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
          @else
            <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold rounded-2xl shadow-xl shadow-blue-600/20 hover:shadow-blue-600/30 transition-all duration-300 text-center flex items-center justify-center gap-2 group">
              Masuk Ke Sistem
              <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
          @endauth
          <a href="#fitur" class="w-full sm:w-auto px-8 py-4 border-2 border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 font-bold rounded-2xl transition-all duration-300 text-center">
            Lihat Fitur Lengkap
          </a>
        </div>

        <!-- Metrics Counters (Trigger Count-up on scroll) -->
        <div class="grid grid-cols-3 gap-6 border-t border-slate-100 pt-8 max-w-lg">
          <div>
            <div class="text-2xl md:text-3xl font-extrabold text-slate-900 flex items-center gap-0.5">
              <span>Rp</span><span class="count-metric" data-target="8.7">0</span><span>Jt</span>
            </div>
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mt-1">Omzet Bulanan</span>
          </div>
          <div>
            <div class="text-2xl md:text-3xl font-extrabold text-slate-900 flex items-center gap-0.5">
              <span class="count-metric" data-target="128">0</span><span>+</span>
            </div>
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mt-1">Transaksi</span>
          </div>
          <div>
            <div class="text-2xl md:text-3xl font-extrabold text-slate-900 flex items-center gap-0.5">
              <span class="count-metric" data-target="100">0</span><span>%</span>
            </div>
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mt-1">Akurasi Stok</span>
          </div>
        </div>
      </div>

      <!-- HERO VISUAL (Interactive Live POS Mockup Simulator) -->
      <div class="lg:col-span-5 relative w-full reveal-item transform translate-y-10 opacity-0 transition-all duration-1000 delay-200">
        <!-- Mockup Card Frame -->
        <div class="relative w-full aspect-[4/5] rounded-3xl border border-slate-200/80 bg-white/95 shadow-2xl p-5 overflow-hidden flex flex-col hover:shadow-blue-600/5 transition-all duration-500">
          
          <!-- Mockup Window Bar -->
          <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4 shrink-0">
            <div class="flex gap-1.5">
              <span class="w-3.5 h-3.5 rounded-full bg-red-400"></span>
              <span class="w-3.5 h-3.5 rounded-full bg-yellow-400"></span>
              <span class="w-3.5 h-3.5 rounded-full bg-green-400"></span>
            </div>
            <div class="text-xs font-bold text-slate-400 bg-slate-50 border border-slate-100 px-3 py-1 rounded-lg">POS SIMULATOR - Live</div>
            <div class="w-10"></div>
          </div>

          <!-- Mockup Cashier Interface Area -->
          <div class="flex-1 min-h-0 flex flex-col gap-4 relative">
            
            <!-- Active Search input -->
            <div class="relative flex items-center shrink-0">
              <div class="absolute left-3.5 text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              </div>
              <input type="text" id="simSearch" readonly value="" placeholder="Cari barang..." class="w-full pl-9 pr-4 py-2 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50/50 outline-none text-slate-700">
            </div>

            <!-- Product Cards grid -->
            <div class="grid grid-cols-2 gap-2.5 shrink-0">
              <div id="simProd-0" class="p-2.5 border border-slate-150 rounded-xl bg-white flex flex-col justify-between h-20 transition-all duration-300">
                <div class="text-[11px] font-bold text-slate-700">Kopi Susu Aren</div>
                <div class="flex justify-between items-center">
                  <span class="text-[10px] font-bold text-blue-600">Rp 18.000</span>
                  <span class="text-[9px] font-semibold px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded">Stok: 42</span>
                </div>
              </div>
              <div id="simProd-1" class="p-2.5 border border-slate-150 rounded-xl bg-white flex flex-col justify-between h-20 transition-all duration-300">
                <div class="text-[11px] font-bold text-slate-700">Roti Bakar Keju</div>
                <div class="flex justify-between items-center">
                  <span class="text-[10px] font-bold text-blue-600">Rp 20.000</span>
                  <span class="text-[9px] font-semibold px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded">Stok: 15</span>
                </div>
              </div>
            </div>

            <!-- Cart Section -->
            <div class="flex-1 border border-slate-200/80 rounded-2xl bg-slate-50/40 p-3.5 flex flex-col min-h-0">
              <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2.5">Keranjang Belanja</div>
              <!-- Cart List -->
              <div class="flex-1 overflow-y-auto space-y-2 min-h-0 pr-1" id="simCartList">
                <div class="text-xs text-slate-400 italic text-center py-4">Belum ada item terpilih</div>
              </div>
              
              <!-- Total -->
              <div class="border-t border-slate-200/60 pt-3 mt-3 flex justify-between items-center shrink-0">
                <span class="text-xs font-bold text-slate-500">Total Akhir:</span>
                <span class="text-sm font-extrabold text-blue-600" id="simTotal">Rp 0</span>
              </div>
            </div>

            <!-- Checkout / Bayar Action Button -->
            <button id="simPayBtn" disabled class="w-full py-3 bg-blue-600 text-white font-bold rounded-2xl text-xs hover:bg-blue-700 active:scale-95 transition-all duration-300 disabled:bg-slate-200 disabled:text-slate-400 shrink-0">
              Bayar Transaksi
            </button>

            <!-- TRANSACTION SUCCESS OVERLAY (SVG Checkmark Drawing & Receipt Print) -->
            <div id="simSuccessOverlay" class="absolute inset-0 bg-white/95 rounded-2xl flex flex-col items-center justify-center p-6 transition-all duration-500 scale-95 opacity-0 pointer-events-none z-10">
              
              <!-- Green Pulse Circle -->
              <div class="w-16 h-16 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center checkmark-wrapper mb-4">
                <svg class="w-8 h-8 text-emerald-500" viewBox="0 0 52 52">
                  <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                  <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
              </div>
              <h4 class="text-sm font-extrabold text-slate-800 mb-1">Transaksi Berhasil!</h4>
              <p class="text-[10px] text-slate-400 mb-4">Stok inventori diperbarui secara real-time</p>
              
              <!-- Simulated Printed Receipt -->
              <div id="simReceipt" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 flex flex-col gap-1.5 opacity-0 transform translate-y-4">
                <div class="text-[9px] font-extrabold text-center text-slate-400 border-b border-dashed border-slate-200 pb-1.5">AEROSTOCK RECEIPT</div>
                <div class="flex justify-between text-[10px] font-medium text-slate-600">
                  <span>Kopi Susu Aren (x1)</span>
                  <span>Rp 18.000</span>
                </div>
                <div class="flex justify-between text-[10px] font-medium text-slate-600 border-b border-dashed border-slate-200 pb-1.5">
                  <span>Roti Bakar Keju (x1)</span>
                  <span>Rp 20.000</span>
                </div>
                <div class="flex justify-between text-[10px] font-extrabold text-blue-600">
                  <span>TOTAL TUNAI</span>
                  <span>Rp 38.000</span>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

    </section>

    <!-- PRODUCT FEATURES (Tailwind Grids, Hover Animations & Reveals) -->
    <section class="border-t border-slate-200/80 bg-white py-24 md:py-32" id="fitur">
      <div class="max-w-7xl mx-auto px-6 md:px-12">
        <div class="max-w-xl mb-16 reveal-item transform translate-y-10 opacity-0 transition-all duration-1000">
          <span class="text-xs font-extrabold text-blue-600 uppercase tracking-widest block mb-3">FITUR UTAMA</span>
          <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight">
            Dirancang Sesuai Kebutuhan Operasional Toko UMKM
          </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- CARD 1 -->
          <article class="p-8 border border-slate-200/60 hover:border-blue-100 bg-white rounded-2xl hover:shadow-2xl hover:shadow-blue-600/[0.04] hover:-translate-y-2 transition-all duration-300 group reveal-item transform translate-y-10 opacity-0 transition-all duration-1000">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900 mb-3">Secure Authentication</h3>
            <p class="text-sm text-slate-500 leading-relaxed">
              Login dan register aman dengan pemisahan hak akses antara Admin (manajer) dan Kasir (operator lapangan).
            </p>
          </article>

          <!-- CARD 2 -->
          <article class="p-8 border border-slate-200/60 hover:border-blue-100 bg-white rounded-2xl hover:shadow-2xl hover:shadow-blue-600/[0.04] hover:-translate-y-2 transition-all duration-300 group reveal-item transform translate-y-10 opacity-0 transition-all duration-1000 delay-100">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900 mb-3">Smart Inventori</h3>
            <p class="text-sm text-slate-500 leading-relaxed">
              Form CRUD stok produk, manajemen kategori, supplier, dan peringatan batas stok minimum otomatis.
            </p>
          </article>

          <!-- CARD 3 -->
          <article class="p-8 border border-slate-200/60 hover:border-blue-100 bg-white rounded-2xl hover:shadow-2xl hover:shadow-blue-600/[0.04] hover:-translate-y-2 transition-all duration-300 group reveal-item transform translate-y-10 opacity-0 transition-all duration-1000 delay-200">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900 mb-3">Kasir POS Cepat</h3>
            <p class="text-sm text-slate-500 leading-relaxed">
              Transaksi kasir yang cepat, input jumlah dinamis, validasi otomatis sisa stok, dan ringkasan cetak struk belanja.
            </p>
          </article>

          <!-- CARD 4 -->
          <article class="p-8 border border-slate-200/60 hover:border-blue-100 bg-white rounded-2xl hover:shadow-2xl hover:shadow-blue-600/[0.04] hover:-translate-y-2 transition-all duration-300 group reveal-item transform translate-y-10 opacity-0 transition-all duration-1000 delay-300">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900 mb-3">Laporan Grafik</h3>
            <p class="text-sm text-slate-500 leading-relaxed">
              Pantau laporan transaksi, metode bayar (tunai/Qris), margin keuntungan, dan stok menipis dengan infografik.
            </p>
          </article>
        </div>
      </div>
    </section>

    <!-- RETAIL WORKFLOW (Tailwind Layout, Connecting Line, Hover Card Scale) -->
    <section class="bg-slate-50/50 py-24 md:py-32 border-t border-b border-slate-200/70 relative" id="alur">
      
      <!-- Interactive Grid Line connecting circles -->
      <div class="absolute top-[52%] left-0 w-full h-[3px] bg-slate-200 -z-10 hidden md:block max-w-7xl mx-auto px-16">
        <div class="h-full bg-blue-600 w-0 transition-all duration-[1.5s]" id="workflowTimelineLine"></div>
      </div>

      <div class="max-w-7xl mx-auto px-6 md:px-12">
        <div class="text-center max-w-xl mx-auto mb-20 reveal-item transform translate-y-10 opacity-0 transition-all duration-1000">
          <span class="text-xs font-extrabold text-blue-600 uppercase tracking-widest block mb-3">ALUR TOKO</span>
          <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight">
            Alur Kerja Integrasi Satu Sistem
          </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
          <!-- WORKFLOW 1 -->
          <article class="p-8 border border-slate-200 bg-white rounded-3xl flex flex-col hover:shadow-xl hover:scale-[1.02] transition-all duration-300 reveal-item transform translate-y-10 opacity-0 transition-all duration-1000">
            <span class="w-10 h-10 bg-blue-600 text-white font-extrabold rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-blue-600/20 text-sm">
              01
            </span>
            <h3 class="text-lg font-bold text-slate-950 mb-3">Input Produk</h3>
            <p class="text-sm text-slate-500 leading-relaxed">
              Admin mendaftarkan katalog produk, kategori, harga beli, harga jual, serta supplier pengirim barang.
            </p>
          </article>

          <!-- WORKFLOW 2 -->
          <article class="p-8 border border-slate-200 bg-white rounded-3xl flex flex-col hover:shadow-xl hover:scale-[1.02] transition-all duration-300 reveal-item transform translate-y-10 opacity-0 transition-all duration-1000 delay-100">
            <span class="w-10 h-10 bg-blue-600 text-white font-extrabold rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-blue-600/20 text-sm">
              02
            </span>
            <h3 class="text-lg font-bold text-slate-950 mb-3">Transaksi Kasir</h3>
            <p class="text-sm text-slate-500 leading-relaxed">
              Kasir memilih item, menginput total kuantitas pembayaran, mencetak nota struk, dan sisa stok langsung terpotong.
            </p>
          </article>

          <!-- WORKFLOW 3 -->
          <article class="p-8 border border-slate-200 bg-white rounded-3xl flex flex-col hover:shadow-xl hover:scale-[1.02] transition-all duration-300 reveal-item transform translate-y-10 opacity-0 transition-all duration-1000 delay-200">
            <span class="w-10 h-10 bg-blue-600 text-white font-extrabold rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-blue-600/20 text-sm">
              03
            </span>
            <h3 class="text-lg font-bold text-slate-950 mb-3">Laporan Grafik</h3>
            <p class="text-sm text-slate-500 leading-relaxed">
              Pemilik toko melihat diagram pendapatan harian, barang paling laku, dan info rekap restok dari supplier.
            </p>
          </article>
        </div>
      </div>
    </section>

    <!-- CONTACT / FOOTER (Tailwind Responsive Layout) -->
    <section class="max-w-7xl mx-auto px-6 md:px-12 py-24 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center" id="kontak">
      <div class="reveal-item transform translate-y-10 opacity-0 transition-all duration-1000">
        <span class="text-xs font-extrabold text-blue-600 uppercase tracking-widest block mb-3">DIBANGUN UNTUK UMKM</span>
        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight mb-6">
          Kurangi Selisih Stok, Tingkatkan Efisiensi Bisnis
        </h2>
        <p class="text-base text-slate-500 leading-relaxed max-w-lg">
          AeroStock didesain ramah pengguna dan ringan untuk kasir minimarket, butik, apotek, dan kedai retail digital.
        </p>
      </div>

      <!-- Support card box -->
      <div class="p-8 md:p-10 border border-slate-200 bg-white rounded-3xl shadow-lg relative reveal-item transform translate-y-10 opacity-0 transition-all duration-1000 delay-150">
        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-bl-[100px] pointer-events-none"></div>
        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-ping"></span>
          AeroStock Technical Support
        </h3>
        <div class="space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 text-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-700">support@aerostock.local</span>
          </div>
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 text-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-700">Jl. Retail Digital No. 10, Surabaya</span>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- FOOTER CREDIT (Removed 'powered by tailwind') -->
  <footer class="border-t border-slate-200/80 bg-white py-8">
    <div class="max-w-7xl mx-auto px-6 md:px-12 flex justify-between items-center text-xs font-semibold text-slate-400">
      <span>&copy; 2026 AeroStock Retail System.</span>
    </div>
  </footer>

  <!-- DYNAMIC ANIMATION SCRIPTS -->
  <script>
    // ── Mobile Menu toggle ──
    function toggleLandingMenu(button) {
      const menu = document.getElementById('mobileMenu');
      menu.classList.toggle('hidden');
      menu.classList.toggle('flex');
    }
    
    function closeMobileMenu() {
      const menu = document.getElementById('mobileMenu');
      menu.classList.add('hidden');
      menu.classList.remove('flex');
    }

    // ── Morphing text keywords (Typing effect) ──
    const phrases = ["Lebih Cepat", "Sangat Mudah", "Real-Time", "Lebih Akurat"];
    let currentPhraseIndex = 0;
    const morphingTextEl = document.getElementById('morphingText');

    function cyclePhrases() {
      morphingTextEl.style.opacity = '0';
      setTimeout(() => {
        currentPhraseIndex = (currentPhraseIndex + 1) % phrases.length;
        morphingTextEl.innerText = phrases[currentPhraseIndex];
        morphingTextEl.style.opacity = '1';
      }, 400);
    }
    setInterval(cyclePhrases, 3500);

    // ── Count-up counter helper ──
    function animateValue(obj, start, end, duration) {
      let startTimestamp = null;
      const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        
        let val = progress * (end - start) + start;
        // Format if decimal
        if (end % 1 !== 0) {
          obj.innerHTML = val.toFixed(1);
        } else {
          obj.innerHTML = Math.floor(val);
        }
        
        if (progress < 1) {
          window.requestAnimationFrame(step);
        }
      };
      window.requestAnimationFrame(step);
    }

    // ── Scroll Reveal Intersection Observer ──
    const revealItems = document.querySelectorAll('.reveal-item');
    const metricItems = document.querySelectorAll('.count-metric');
    let countersTriggered = false;

    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.remove('opacity-0', 'translate-y-10');
          entry.target.classList.add('opacity-100', 'translate-y-0');
          
          // Trigger timeline bar grow
          if (entry.target.id === 'alur') {
            const timelineLine = document.getElementById('workflowTimelineLine');
            if (timelineLine) timelineLine.style.width = '100%';
          }
        }
      });
    }, { threshold: 0.1 });

    revealItems.forEach(item => {
      revealObserver.observe(item);
    });

    const metricsObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !countersTriggered) {
          countersTriggered = true;
          metricItems.forEach(item => {
            const targetVal = parseFloat(item.getAttribute('data-target'));
            animateValue(item, 0, targetVal, 1500);
          });
        }
      });
    }, { threshold: 0.5 });

    if (metricItems.length > 0) {
      metricsObserver.observe(metricItems[0]);
    }

    // ── Interactive Live POS Mockup loop ──
    const simSearch = document.getElementById('simSearch');
    const simCartList = document.getElementById('simCartList');
    const simTotal = document.getElementById('simTotal');
    const simPayBtn = document.getElementById('simPayBtn');
    const simSuccessOverlay = document.getElementById('simSuccessOverlay');
    const simReceipt = document.getElementById('simReceipt');
    const simProd0 = document.getElementById('simProd-0');
    const simProd1 = document.getElementById('simProd-1');

    const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

    async function runPosSimulation() {
      while (true) {
        // RESET STATE
        simSearch.value = "";
        simCartList.innerHTML = `<div class="text-xs text-slate-400 italic text-center py-4">Belum ada item terpilih</div>`;
        simTotal.innerText = "Rp 0";
        simPayBtn.disabled = true;
        simPayBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'pulse-glow');
        simPayBtn.classList.add('bg-slate-200', 'text-slate-400');
        simSuccessOverlay.classList.remove('opacity-100', 'scale-100');
        simSuccessOverlay.classList.add('opacity-0', 'scale-95');
        simReceipt.classList.remove('animate-receipt-print');
        simReceipt.classList.add('opacity-0', 'translate-y-4');
        simProd0.classList.remove('border-blue-500', 'bg-blue-50/20');
        simProd1.classList.remove('border-blue-500', 'bg-blue-50/20');

        await sleep(1500);

        // STEP 1: Search Item
        const searchVal = "Kopi";
        for (let i = 0; i < searchVal.length; i++) {
          simSearch.value += searchVal[i];
          await sleep(150);
        }
        await sleep(400);

        // Highlight product 0
        simProd0.classList.add('border-blue-500', 'bg-blue-50/20', 'scale-105');
        await sleep(500);
        simProd0.classList.remove('scale-105');

        // Add Product 0 to Cart
        simCartList.innerHTML = `
          <div class="flex justify-between items-center bg-white p-2 rounded-xl border border-slate-100 shadow-sm animate-in">
            <div class="flex flex-col">
              <span class="text-[11px] font-bold text-slate-700">Kopi Susu Aren</span>
              <span class="text-[10px] text-slate-400">Rp 18.000</span>
            </div>
            <span class="text-xs font-extrabold text-blue-600">x1</span>
          </div>
        `;
        simTotal.innerText = "Rp 18.000";
        await sleep(1500);

        // STEP 2: Search Item 2
        simSearch.value = "";
        const searchVal2 = "Roti";
        for (let i = 0; i < searchVal2.length; i++) {
          simSearch.value += searchVal2[i];
          await sleep(120);
        }
        await sleep(400);

        // Highlight product 1
        simProd1.classList.add('border-blue-500', 'bg-blue-50/20', 'scale-105');
        await sleep(500);
        simProd1.classList.remove('scale-105');

        // Add Product 1 to Cart
        simCartList.innerHTML += `
          <div class="flex justify-between items-center bg-white p-2 rounded-xl border border-slate-100 shadow-sm animate-in">
            <div class="flex flex-col">
              <span class="text-[11px] font-bold text-slate-700">Roti Bakar Keju</span>
              <span class="text-[10px] text-slate-400">Rp 20.000</span>
            </div>
            <span class="text-xs font-extrabold text-blue-600">x1</span>
          </div>
        `;
        simTotal.innerText = "Rp 38.000";
        await sleep(1500);

        // STEP 3: Enable Payment button
        simPayBtn.disabled = false;
        simPayBtn.classList.remove('bg-slate-200', 'text-slate-400');
        simPayBtn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'pulse-glow');
        await sleep(1200);

        // Click Payment
        simPayBtn.classList.add('scale-95');
        await sleep(200);
        simPayBtn.classList.remove('scale-95');
        await sleep(400);

        // STEP 4: Transaction Success Overlay
        simSuccessOverlay.classList.remove('opacity-0', 'scale-95');
        simSuccessOverlay.classList.add('opacity-100', 'scale-100');
        await sleep(1000);

        // Step 5: Printed receipt animated slide-in
        simReceipt.classList.remove('opacity-0', 'translate-y-4');
        simReceipt.classList.add('animate-receipt-print');
        
        await sleep(5000); // Display receipt before repeating
      }
    }

    // Launch POS simulation
    runPosSimulation();
  </script>
</body>
</html>
