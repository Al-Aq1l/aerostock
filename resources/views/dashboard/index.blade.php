@extends('layouts.app')

@section('title', 'Dasbor')
@section('breadcrumb', 'Dasbor')
@section('page-title', 'Dasbor')

@section('content')
<div class="page-body">

  {{-- ── Kartu Ringkasan ──────────────────────────────────────── --}}
  <div class="stat-grid">
    <div class="stat-card animate-in" style="animation-delay:0.05s">
      <div class="stat-icon blue">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="1" x2="12" y2="23"/>
          <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
        </svg>
      </div>
      <div class="stat-body">
        <div class="stat-label">Pendapatan Hari Ini</div>
        <div class="stat-value">Rp{{ number_format($todayRevenue, 0, ',', '.') }}</div>
        <div class="stat-delta up">Aktif hari ini</div>
      </div>
    </div>

    <div class="stat-card animate-in" style="animation-delay:0.10s">
      <div class="stat-icon mint">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
          <polyline points="10 9 9 9 8 9"/>
        </svg>
      </div>
      <div class="stat-body">
        <div class="stat-label">Transaksi Hari Ini</div>
        <div class="stat-value">{{ $todaySales }}</div>
        <div class="stat-delta up">Total pesanan</div>
      </div>
    </div>

    <div class="stat-card animate-in" style="animation-delay:0.15s">
      <div class="stat-icon amber">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
        </svg>
      </div>
      <div class="stat-body">
        <div class="stat-label">Total Produk</div>
        <div class="stat-value">{{ $totalProducts }}</div>
        <div class="stat-delta neu">Produk aktif</div>
      </div>
    </div>

    <div class="stat-card animate-in" style="animation-delay:0.20s">
      <div class="stat-icon danger">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
          <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
      </div>
      <div class="stat-body">
        <div class="stat-label">Stok Menipis</div>
        <div class="stat-value">{{ $lowStockCount }}</div>
        <div class="stat-delta {{ $lowStockCount > 0 ? 'down' : 'up' }}">
          {{ $lowStockCount > 0 ? 'Perlu restok' : 'Semua aman' }}
        </div>
      </div>
    </div>
  </div>

  {{-- ── Grafik & Peringatan ───────────────────────────────────── --}}
  <div class="dashboard-grid">

    {{-- Grafik Penjualan --}}
    <div class="card animate-in" style="animation-delay:0.25s">
      <div class="card-header">
        <div>
          <div class="card-title">Ringkasan Penjualan</div>
          <div style="font-size:12px;color:var(--slate-400);margin-top:2px">Pendapatan · 30 hari terakhir</div>
        </div>
        <div style="display:flex;gap:6px">
          <button class="btn btn-secondary" style="padding:5px 12px;font-size:12px" onclick="toggleChartMode('revenue')" id="btn-revenue">Pendapatan</button>
          <button class="btn btn-secondary" style="padding:5px 12px;font-size:12px" onclick="toggleChartMode('orders')" id="btn-orders">Pesanan</button>
        </div>
      </div>
      <div class="card-body">
        <div class="chart-container">
          <canvas id="salesChart"></canvas>
        </div>
      </div>
    </div>

    {{-- Panel Stok Menipis --}}
    <div class="card animate-in" style="animation-delay:0.30s">
      <div class="card-header">
        <div class="card-title">Peringatan Stok</div>
        <a href="{{ route('inventory.index') }}?status=low" class="btn btn-secondary" style="padding:5px 12px;font-size:12px">Lihat Semua</a>
      </div>
      <div class="card-body" style="padding-top:10px">
        @forelse($lowStockItems as $item)
          <div class="alert-item {{ $item->quantity <= 0 ? 'critical' : 'low' }}">
            <div class="alert-item-icon">
              @if($item->quantity <= 0)
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
              @else
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                  <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
              @endif
            </div>
            <div class="alert-item-body">
              <div class="alert-item-name">{{ $item->product->name }}</div>
              <div class="alert-item-detail">{{ $item->product->category->name }} · {{ $item->product->sku }}</div>
            </div>
            <div>
              @if($item->quantity <= 0)
                <span class="badge badge-danger">Habis</span>
              @else
                <span class="badge badge-amber">{{ $item->quantity }} sisa</span>
              @endif
            </div>
          </div>
        @empty
          <div style="text-align:center;padding:28px 0;color:var(--slate-400)">
            <svg style="width:32px;height:32px;stroke:var(--slate-300);margin:0 auto 8px" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            <div style="font-size:13px;font-weight:600">Semua stok dalam kondisi aman</div>
          </div>
        @endforelse
      </div>
    </div>

  </div>

  {{-- ── Transaksi Terakhir ────────────────────────────────────── --}}
  <div class="card animate-in" style="animation-delay:0.35s">
    <div class="card-header">
      <div class="card-title">Transaksi Terakhir</div>
      <span style="font-size:12px;color:var(--slate-400)">10 penjualan terbaru</span>
    </div>
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>No. Referensi</th>
            <th>Jumlah Item</th>
            <th>Pembayaran</th>
            <th>Status</th>
            <th>Total</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($recentSales as $sale)
          <tr>
            <td>
              <span style="font-family:monospace;font-weight:700;color:var(--accent)">{{ $sale->reference }}</span>
            </td>
            <td>{{ $sale->items->count() }} item</td>
            <td>
              @php
                $payLabels = ['cash'=>'Tunai','card'=>'Kartu','ewallet'=>'E-Wallet'];
              @endphp
              <span class="badge badge-slate">{{ $payLabels[$sale->payment_method] ?? $sale->payment_method }}</span>
            </td>
            <td>
              @if($sale->status === 'completed')
                <span class="badge badge-mint">Selesai</span>
              @elseif($sale->status === 'refunded')
                <span class="badge badge-danger">Dikembalikan</span>
              @else
                <span class="badge badge-amber">Pending</span>
              @endif
            </td>
            <td style="font-weight:700;color:var(--charcoal)">Rp{{ number_format($sale->total, 0, ',', '.') }}</td>
            <td style="color:var(--slate-400);font-size:12.5px">{{ $sale->created_at->format('d M, H:i') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
  const labels  = @json($chartLabels);
  const revenue = @json($chartRevenue);
  const orders  = @json($chartOrders);

  const ctx = document.getElementById('salesChart').getContext('2d');
  const gradient = ctx.createLinearGradient(0, 0, 0, 210);
  gradient.addColorStop(0, 'rgba(37,99,235,0.12)');
  gradient.addColorStop(1, 'rgba(37,99,235,0.00)');

  let chartMode = 'revenue';

  const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels,
      datasets: [{
        label: 'Pendapatan',
        data: revenue,
        borderColor: '#2563EB',
        backgroundColor: gradient,
        borderWidth: 2,
        pointRadius: 0,
        pointHoverRadius: 4,
        pointHoverBackgroundColor: '#2563EB',
        tension: 0.4,
        fill: true,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#111827',
          titleFont: { family: 'Inter', size: 11, weight: '600' },
          bodyFont:  { family: 'Inter', size: 13, weight: '700' },
          padding: 10,
          cornerRadius: 8,
          callbacks: {
            label: ctx => chartMode === 'revenue'
              ? `  Rp${ctx.raw.toLocaleString('id-ID')}`
              : `  ${ctx.raw} pesanan`
          }
        }
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { font: { family: 'Inter', size: 11 }, color: '#9CA3AF', maxTicksLimit: 10 },
          border: { display: false }
        },
        y: {
          grid: { color: 'rgba(0,0,0,0.04)' },
          ticks: {
            font: { family: 'Inter', size: 11 },
            color: '#9CA3AF',
            callback: v => chartMode === 'revenue' ? `Rp${v.toLocaleString('id-ID')}` : v
          },
          border: { display: false }
        }
      },
      interaction: { mode: 'index', intersect: false }
    }
  });

  function toggleChartMode(mode) {
    chartMode = mode;
    const newGrad = ctx.createLinearGradient(0, 0, 0, 210);
    if (mode === 'revenue') {
      newGrad.addColorStop(0, 'rgba(37,99,235,0.12)');
      newGrad.addColorStop(1, 'rgba(37,99,235,0.00)');
      salesChart.data.datasets[0].data = revenue;
      salesChart.data.datasets[0].borderColor = '#2563EB';
      document.getElementById('btn-revenue').style.cssText = 'background:var(--accent-light);color:var(--accent);border-color:var(--accent)';
      document.getElementById('btn-orders').style.cssText  = '';
    } else {
      newGrad.addColorStop(0, 'rgba(16,185,129,0.12)');
      newGrad.addColorStop(1, 'rgba(16,185,129,0.00)');
      salesChart.data.datasets[0].data = orders;
      salesChart.data.datasets[0].borderColor = '#10B981';
      document.getElementById('btn-orders').style.cssText  = 'background:var(--mint-light);color:var(--mint);border-color:var(--mint)';
      document.getElementById('btn-revenue').style.cssText = '';
    }
    salesChart.data.datasets[0].backgroundColor = newGrad;
    salesChart.update('active');
  }

  document.getElementById('btn-revenue').style.cssText = 'background:var(--accent-light);color:var(--accent);border-color:var(--accent)';
</script>
@endpush
