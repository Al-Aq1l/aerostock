<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk — AeroStock</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">
  <main class="auth-shell">
    <section class="auth-panel">
      <a href="{{ route('landing') }}" class="auth-brand">
        <img src="{{ asset('logo.png') }}" alt="AeroStock">
      </a>
      <h1>Masuk ke AeroStock</h1>
      <p>Kelola transaksi, stok, dan laporan toko dari satu dashboard.</p>

      @if($errors->any())
        <div class="flash-msg error">
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('login.store') }}">
        @csrf
        <div class="form-group">
          <label for="email">Email</label>
          <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input class="form-control" type="password" id="password" name="password" required>
        </div>
        <label class="check-row">
          <input type="checkbox" name="remember" value="1">
          <span>Ingat saya</span>
        </label>
        <button class="btn btn-primary btn-xl" type="submit">Masuk</button>
      </form>

      <div class="auth-alt">
        Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai kasir</a>
      </div>
    </section>
  </main>
</body>
</html>
