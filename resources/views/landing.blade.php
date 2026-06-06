<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AeroStock — Sistem POS UMKM Retail</title>
  <meta name="description" content="AeroStock membantu UMKM retail mengelola kasir, stok, produk, supplier, dan laporan penjualan secara real-time.">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="landing-page">
  <header class="landing-nav">
    <a href="{{ route('landing') }}" class="landing-logo">
      <img src="{{ asset('logo.png') }}" alt="AeroStock">
    </a>
    <nav>
      @auth
        <a href="{{ auth()->user()->isAdmin() ? route('dashboard') : route('pos.index') }}" class="btn btn-primary">
          {{ auth()->user()->isAdmin() ? 'Buka Dasbor' : 'Buka POS' }}
        </a>
      @else
        <a href="{{ route('login') }}" class="btn btn-secondary">Masuk</a>
        <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
      @endauth
    </nav>
  </header>

  <main>
    <section class="landing-hero">
      <div class="landing-hero-copy">
        <span class="landing-kicker">POS dan inventori untuk UMKM retail</span>
        <h1>AeroStock</h1>
        <p>Platform kasir dan stok yang membantu pemilik toko memantau transaksi, stok masuk-keluar, supplier, dan laporan penjualan dari satu tempat.</p>
        <div class="landing-actions">
          @auth
            <a href="{{ route('pos.index') }}" class="btn btn-primary btn-lg">Mulai Transaksi</a>
          @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Masuk ke Sistem</a>
          @endauth
          <a href="#fitur" class="btn btn-secondary btn-lg">Lihat Fitur</a>
        </div>
      </div>
      <div class="landing-preview">
        <div class="preview-top">
          <span></span><span></span><span></span>
        </div>
        <div class="preview-grid">
          <div class="preview-stat">
            <small>Pendapatan</small>
            <strong>Rp8,7 jt</strong>
          </div>
          <div class="preview-stat">
            <small>Transaksi</small>
            <strong>128</strong>
          </div>
          <div class="preview-chart"></div>
          <div class="preview-list">
            <span>Stok Aman</span>
            <span>Produk Terlaris</span>
            <span>Supplier Aktif</span>
          </div>
        </div>
      </div>
    </section>

    <section class="landing-section" id="fitur">
      <div class="section-heading">
        <span class="landing-kicker">Product Features</span>
        <h2>Fitur sesuai kebutuhan operasional toko</h2>
      </div>
      <div class="feature-grid">
        <article class="feature-card">
          <strong>Secure Authentication</strong>
          <p>Login dan register dengan pemisahan role Admin dan Kasir.</p>
        </article>
        <article class="feature-card">
          <strong>Smart Inventory Forms</strong>
          <p>CRUD produk, kategori, supplier, dan pengaturan batas stok minimum.</p>
        </article>
        <article class="feature-card">
          <strong>POS Dashboard</strong>
          <p>Kasir cepat dengan validasi stok dan ringkasan pembayaran.</p>
        </article>
        <article class="feature-card">
          <strong>Sales Report</strong>
          <p>Laporan transaksi, metode pembayaran, item terjual, dan status penjualan.</p>
        </article>
      </div>
    </section>

    <section class="landing-section landing-contact">
      <div>
        <span class="landing-kicker">Contact / About Us</span>
        <h2>Dibangun untuk toko yang ingin lebih rapi secara digital</h2>
        <p>AeroStock mengurangi selisih stok manual, mempercepat input kasir, dan membuat data penjualan lebih mudah dipantau oleh pemilik UMKM retail.</p>
      </div>
      <div class="contact-box">
        <strong>AeroStock Support</strong>
        <span>support@aerostock.local</span>
        <span>Jl. Retail Digital No. 10, Surabaya</span>
      </div>
    </section>
  </main>
</body>
</html>
