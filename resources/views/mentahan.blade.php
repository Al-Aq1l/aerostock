<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mentahan Dashboard Tailwind</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<!-- Wrapper Utama Halaman -->
<!-- h-screen agar tinggi menyesuaikan layar penuh, flex agar sidebar & konten sejajar -->
<div class="flex h-screen bg-gray-50 font-sans text-gray-900">
  
  <!-- =============================== -->
  <!-- 1. SIDEBAR KIRI                 -->
  <!-- =============================== -->
  <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col">
    <!-- Logo area -->
    <div class="h-16 flex items-center px-6 border-b border-gray-200">
      <h1 class="text-xl font-bold text-indigo-600">AppLogo</h1>
    </div>
    
    <!-- Link Navigasi -->
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
      <a href="#" class="block px-4 py-2.5 bg-indigo-50 text-indigo-700 font-semibold rounded-lg transition-colors">
        Dashboard
      </a>
      <a href="#" class="block px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
        Pesanan
      </a>
      <a href="#" class="block px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
        Pelanggan
      </a>
      <a href="#" class="block px-4 py-2.5 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition-colors">
        Pengaturan
      </a>
    </nav>
  </aside>

  <!-- =============================== -->
  <!-- 2. KONTEN UTAMA (Kanan)         -->
  <!-- =============================== -->
  <!-- flex-1 membuat area ini mengambil seluruh sisa lebar ruang di kanan sidebar -->
  <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
    
    <!-- TOPBAR / HEADER -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 z-10">
       <!-- Judul Halaman -->
       <h2 class="text-lg font-semibold text-gray-800">Ringkasan Utama</h2>
       
       <!-- Aksi Topbar (Misal: Notifikasi & Profil) -->
       <div class="flex items-center space-x-4">
          <button class="p-2 text-gray-400 hover:text-gray-600 bg-gray-50 rounded-full border border-transparent hover:border-gray-200 transition-all">
            🔔
          </button>
          <div class="w-9 h-9 bg-indigo-200 rounded-full border-2 border-white shadow-sm cursor-pointer"></div>
       </div>
    </header>

    <!-- AREA KONTEN DASHBOARD (Widget & Angka) -->
    <section class="p-6 sm:p-8 flex-1">
      
      <!-- Deretan Card Statistik (Grid layout) -->
      <!-- Di layar HP (default) 1 kolom, layar md+ jadi 3 kolom -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-sm font-medium text-gray-500">Total Pendapatan</h3>
          <div class="mt-2 flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-gray-900">Rp 45.3M</span>
            <span class="text-sm text-green-600 font-semibold">+12%</span>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-sm font-medium text-gray-500">Pengguna Aktif</h3>
          <div class="mt-2 flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-gray-900">2,451</span>
            <span class="text-sm text-green-600 font-semibold">+4.5%</span>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-sm font-medium text-gray-500">Pesanan Baru</h3>
          <div class="mt-2 flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-gray-900">342</span>
            <span class="text-sm text-red-500 font-semibold">-1.2%</span>
          </div>
        </div>

      </div>

      <!-- Area Tabel atau Grafik -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h3>
            <button class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat Semua</button>
        </div>
        <div class="p-6 text-center text-gray-500 py-16">
          <p>Tabel atau grafik rincian diletakkan di area ini...</p>
        </div>
      </div>

    </section>
  </main>
</div>
</body>
</html>
