<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar — AeroStock</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">
  <main class="auth-shell">
    <section class="auth-panel">
      <a href="{{ route('landing') }}" class="auth-brand">
        <img src="{{ asset('logo.png') }}" alt="AeroStock">
      </a>
      <h1>Buat Akun Kasir</h1>
      <p>Akun baru otomatis memakai role kasir untuk menjaga data master tetap aman.</p>

      @if($errors->any())
        <div class="flash-msg error">
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('register.store') }}">
        @csrf
        <div class="form-group">
          <label for="name">Nama</label>
          <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-grid">
          <div class="form-group">
            <label for="password">Password</label>
            <input class="form-control" type="password" id="password" name="password" required>
          </div>
          <div class="form-group">
            <label for="password_confirmation">Konfirmasi</label>
            <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" required>
          </div>
        </div>
        <button class="btn btn-primary btn-xl" type="submit">Daftar</button>
      </form>

      <div class="auth-alt">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
      </div>
    </section>
  </main>
</body>
</html>
