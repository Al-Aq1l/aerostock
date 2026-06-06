<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Kasir') — AeroStock</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="cashier-pos-page">
  <header class="cashier-topbar">
    <div class="cashier-user-chip">
      <span class="cashier-user-avatar">{{ strtoupper(substr(auth()->user()?->name ?? 'K', 0, 1)) }}</span>
      <span class="cashier-user-meta">
        <strong>{{ auth()->user()?->name ?? 'Kasir' }}</strong>
        <small>Kasir</small>
      </span>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="cashier-logout-form">
      @csrf
      <button type="submit" class="cashier-logout-btn" title="Keluar">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Logout
      </button>
    </form>
  </header>

  @yield('content')

  @stack('modals')
  @stack('scripts')
</body>
</html>
