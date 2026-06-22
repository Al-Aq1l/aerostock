@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('breadcrumb', 'Laporan')
@section('page-title', 'Laporan Penjualan')

@php
  $paymentLabels = [
    'cash' => 'Tunai',
    'card' => 'Kartu',
    'ewallet' => 'QRIS',
  ];
@endphp

@section('content')
<div class="page-body">

  <div class="page-header">
    <div>
      <div class="page-header-title">Laporan Penjualan</div>
      <div class="page-header-subtitle">Pantau transaksi, pendapatan, item terjual, dan metode pembayaran</div>
    </div>
  </div>

  <div class="stat-grid report-stat-grid">
    <div class="stat-card">
      <div class="stat-icon blue">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="1" x2="12" y2="23"/>
          <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
        </svg>
      </div>
      <div class="stat-body">
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value report-stat-value">Rp{{ number_format($summary['revenue'], 0, ',', '.') }}</div>
        <div class="stat-delta neu">Dari transaksi terfilter</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon mint">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
      </div>
      <div class="stat-body">
        <div class="stat-label">Transaksi</div>
        <div class="stat-value report-stat-value">{{ number_format($summary['transactions'], 0, ',', '.') }}</div>
        <div class="stat-delta neu">Jumlah pesanan</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon amber">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
        </svg>
      </div>
      <div class="stat-body">
        <div class="stat-label">Item Terjual</div>
        <div class="stat-value report-stat-value">{{ number_format($summary['items_sold'], 0, ',', '.') }}</div>
        <div class="stat-delta neu">Total kuantitas</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon danger">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12 6 12 12 16 14"/>
        </svg>
      </div>
      <div class="stat-body">
        <div class="stat-label">Rata-rata Pesanan</div>
        <div class="stat-value report-stat-value">Rp{{ number_format($summary['average_order'], 0, ',', '.') }}</div>
        <div class="stat-delta neu">Nilai per transaksi</div>
      </div>
    </div>
  </div>

  <form method="GET" action="{{ route('reports.index') }}">
    <div class="filters-row">
      <div class="filter-search">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari referensi atau produk...">
      </div>
      <input type="date" class="filter-select" name="date_from" value="{{ request('date_from') }}" title="Tanggal mulai">
      <input type="date" class="filter-select" name="date_to" value="{{ request('date_to') }}" title="Tanggal akhir">
      <select name="payment_method" class="filter-select" onchange="this.form.submit()">
        <option value="">Semua Pembayaran</option>
        @foreach($paymentLabels as $value => $label)
          <option value="{{ $value }}" {{ request('payment_method') === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn btn-primary">Terapkan</button>
      @if(request()->hasAny(['search', 'date_from', 'date_to', 'payment_method']))
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">Reset</a>
      @endif
    </div>
  </form>

  <div class="card">
    <div class="card-header">
      <div>
        <div class="card-title">Riwayat Transaksi</div>
        <div style="font-size:12px;color:var(--slate-400);margin-top:2px">{{ $sales->total() }} transaksi ditemukan</div>
      </div>
    </div>

    @if($sales->isEmpty())
      <div class="card-body">
        <div style="text-align:center;padding:42px 20px;color:var(--slate-400);font-size:14px">
          Belum ada transaksi yang sesuai dengan filter.
        </div>
      </div>
    @else
      <div class="table-wrapper reports-table-wrapper">
        <table class="reports-table">
          <thead>
            <tr>
              <th>No. Referensi</th>
              <th>Tanggal</th>
              <th>Item</th>
              <th>Pembayaran</th>
              <th>Subtotal</th>
              <th>Pajak</th>
              <th>Total</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($sales as $sale)
              @php
                $firstItem = $sale->items->first();
                $itemCount = $sale->items->count();
                $quantityTotal = $sale->items->sum('quantity');
              @endphp
              <tr>
                <td>
                  <div style="font-weight:700;color:var(--accent)">{{ $sale->reference }}</div>
                  <div style="font-size:11px;color:var(--slate-400);margin-top:2px">#{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</div>
                </td>
                <td>
                  <div style="font-weight:600;color:var(--charcoal)">{{ $sale->created_at->format('d M Y') }}</div>
                  <div style="font-size:11px;color:var(--slate-400);margin-top:2px">{{ $sale->created_at->format('H:i') }}</div>
                </td>
                <td>
                  <div class="product-name-cell">{{ $firstItem?->product?->name ?? 'Produk dihapus' }}</div>
                  <div style="font-size:11px;color:var(--slate-400);margin-top:2px">
                    {{ $quantityTotal }} item
                    @if($itemCount > 1)
                      · {{ $itemCount - 1 }} produk lainnya
                    @endif
                  </div>
                </td>
                <td>
                  <span class="badge badge-slate">{{ $paymentLabels[$sale->payment_method] ?? ucfirst($sale->payment_method) }}</span>
                </td>
                <td>Rp{{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                <td style="color:var(--slate-400)">Rp{{ number_format($sale->tax, 0, ',', '.') }}</td>
                <td style="font-weight:800;color:var(--charcoal)">Rp{{ number_format($sale->total, 0, ',', '.') }}</td>
                <td>
                  <span class="badge badge-mint">{{ $sale->status === 'completed' ? 'Selesai' : ucfirst($sale->status) }}</span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if($sales->hasPages())
        <div class="pagination">
          @if($sales->onFirstPage())
            <span class="page-link disabled">&#8249;</span>
          @else
            <a class="page-link" href="{{ $sales->previousPageUrl() }}">&#8249;</a>
          @endif
          @foreach($sales->getUrlRange(max(1, $sales->currentPage()-2), min($sales->lastPage(), $sales->currentPage()+2)) as $page => $url)
            <a class="page-link {{ $page == $sales->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
          @endforeach
          @if($sales->hasMorePages())
            <a class="page-link" href="{{ $sales->nextPageUrl() }}">&#8250;</a>
          @else
            <span class="page-link disabled">&#8250;</span>
          @endif
        </div>
      @endif
    @endif
  </div>

</div>
@endsection
