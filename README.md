# AeroStock - Sistem POS dan Inventori UMKM Retail

AeroStock adalah aplikasi web berbasis Laravel untuk membantu UMKM retail mengelola transaksi kasir, stok barang, produk, kategori, supplier, dan laporan penjualan dalam satu platform.

Proyek ini disusun berdasarkan requirement brainstorming praktikum:

- Nama: Al Aqil Bintang Samudra
- NPM: 13.2023.1.01197
- Tema: Sistem POS dan manajemen inventori UMKM retail

## Latar Belakang

Berdasarkan riset operasional UMKM retail, pengelolaan inventaris dan transaksi masih sering mengalami beberapa kendala:

- Pencatatan manual menggunakan buku atau spreadsheet dapat menyebabkan selisih antara stok fisik dan data digital.
- Banyak sistem POS sederhana belum memiliki landing page yang informatif untuk branding toko.
- Data transaksi dan stok perlu dilindungi dengan sistem login dan pembagian hak akses.
- Kasir membutuhkan proses input transaksi yang cepat, tetapi tetap tervalidasi agar stok dan harga tidak keliru.

## Tujuan

AeroStock dibuat untuk:

- Mengimplementasikan materi praktikum Laravel seperti CRUD, MVC, Eloquent ORM, authentication, Blade templating, pagination, dan layouting.
- Menyediakan alur pengelolaan stok yang lebih sistematis dan mudah dipantau.
- Membantu toko memiliki tampilan web yang profesional melalui landing page responsif.
- Memisahkan akses Admin dan Kasir agar data master lebih aman.

## Fitur Utama

### 1. Professional Landing Page

Halaman awal publik yang berisi:

- Hero section AeroStock
- Product features
- Contact / About Us
- Tombol masuk atau daftar

Route:

```text
/
```

### 2. Secure Authentication System

Sistem autentikasi untuk menjaga akses aplikasi.

Fitur:

- Login
- Register
- Logout
- Role Admin
- Role Kasir

Route:

```text
/login
/register
```

### 3. Role Admin dan Kasir

Admin dapat mengakses seluruh fitur manajemen:

- Dashboard
- POS
- Produk
- Kategori
- Supplier
- Inventori
- Laporan

Kasir diarahkan ke tampilan POS full screen agar proses transaksi lebih fokus. Tampilan kasir hanya menampilkan POS dan tombol logout kecil.

### 4. POS Dashboard

Halaman kasir untuk melakukan transaksi penjualan.

Fitur:

- Filter produk berdasarkan kategori
- Keranjang belanja
- Pilihan metode pembayaran: tunai, kartu, e-wallet
- Perhitungan subtotal, pajak, dan total otomatis
- Validasi stok agar tidak menjual melebihi stok tersedia
- Modal transaksi berhasil

Route:

```text
/pos
```

### 5. Smart Inventory Forms

Fitur manajemen stok dan data master.

Data yang dapat dikelola:

- Produk
- Kategori
- Supplier
- Stok barang
- Batas minimum stok

Fitur pendukung:

- Tambah data
- Edit data
- Hapus data
- Search
- Filter
- Pagination
- Status stok: aman, menipis, habis

Route:

```text
/products
/categories
/suppliers
/inventory
```

### 6. Dashboard

Dashboard utama untuk memantau kondisi toko.

Informasi yang ditampilkan:

- Pendapatan hari ini
- Transaksi hari ini
- Total produk aktif
- Jumlah stok menipis
- Grafik penjualan 30 hari terakhir
- Peringatan stok
- Transaksi terbaru

Route:

```text
/dashboard
```

### 7. Laporan Penjualan

Halaman laporan untuk melihat riwayat transaksi.

Informasi yang ditampilkan:

- Nomor referensi transaksi
- Tanggal transaksi
- Metode pembayaran
- Item terjual
- Total transaksi
- Status transaksi

Route:

```text
/reports
```

## Teknologi yang Digunakan

- Laravel 12
- PHP 8.2+
- MySQL
- Blade Template
- Eloquent ORM
- Vite
- CSS Custom berbasis design system AeroStock
- JavaScript untuk interaksi POS

## Struktur Database

Tabel utama:

- `users`
- `categories`
- `suppliers`
- `products`
- `inventory`
- `sales`
- `sale_items`

Relasi utama:

- Category memiliki banyak Product
- Supplier memiliki banyak Product
- Product memiliki satu Inventory
- Sale memiliki banyak SaleItem
- SaleItem terhubung ke Product

## Akun Demo

Setelah menjalankan seeder, tersedia akun:

```text
Admin
Email    : admin@aerostock.test
Password : password

Kasir
Email    : kasir@aerostock.test
Password : password
```

## Cara Menjalankan Project

1. Install dependency PHP:

```bash
composer install
```

2. Install dependency frontend:

```bash
npm install
```

3. Salin file environment:

```bash
cp .env.example .env
```

4. Generate application key:

```bash
php artisan key:generate
```

5. Sesuaikan konfigurasi database pada `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aerostock
DB_USERNAME=root
DB_PASSWORD=
```

6. Jalankan migration dan seeder:

```bash
php artisan migrate --seed
```

7. Build asset:

```bash
npm run build
```

8. Jalankan server:

```bash
php artisan serve
```

Project dapat dibuka melalui:

```text
http://127.0.0.1:8000
```

## Perintah Pengujian

Menjalankan test Laravel:

```bash
php artisan test
```

Melihat route yang tersedia:

```bash
php artisan route:list
```

## Ringkasan Requirement dan Implementasi

| Requirement | Implementasi |
| --- | --- |
| Landing page informatif | Halaman `/` dengan hero, fitur, dan contact/about |
| Login dan register | AuthController dan view auth |
| Admin vs Kasir | Kolom `role` pada users dan Gate `manage-catalog` |
| CRUD produk | ProductController dan halaman products |
| CRUD kategori | CategoryController dan halaman categories |
| CRUD supplier | SupplierController dan halaman suppliers |
| Manajemen stok | InventoryController dan halaman inventory |
| POS kasir | PosController, `public/js/pos.js`, dan tampilan kasir full screen |
| Dashboard grafik dan stok | DashboardController dan view dashboard |
| Laporan transaksi | ReportController dan view reports |
| Pagination dan search | Produk, supplier, inventori, laporan |

## Kesimpulan

AeroStock memenuhi kebutuhan pengguna UMKM retail dengan menyediakan sistem POS, inventori, autentikasi, role pengguna, landing page, dashboard, dan laporan penjualan dalam satu aplikasi Laravel yang terstruktur.
