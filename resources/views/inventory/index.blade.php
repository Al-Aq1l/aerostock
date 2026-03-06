@extends('layouts.app')

@section('title', 'Inventori')
@section('breadcrumb', 'Inventori')
@section('page-title', 'Inventori')

@section('content')
<div class="page-body">

  <div class="page-header">
    <div>
      <div class="page-header-title">Manajemen Inventori</div>
      <div class="page-header-subtitle">Pantau dan atur stok produk secara keseluruhan</div>
    </div>
  </div>

  {{-- Filter --}}
  <form method="GET" action="{{ route('inventory.index') }}">
    <div class="filters-row">
      <div class="filter-search">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk atau SKU...">
      </div>
      <select name="status" class="filter-select" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="ok"   {{ request('status') === 'ok'  ? 'selected' : '' }}>Stok Aman</option>
        <option value="low"  {{ request('status') === 'low' ? 'selected' : '' }}>Stok Menipis</option>
        <option value="out"  {{ request('status') === 'out' ? 'selected' : '' }}>Stok Habis</option>
      </select>
      <button type="submit" class="btn btn-primary">Cari</button>
      @if(request()->hasAny(['search','status']))
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Reset</a>
      @endif
    </div>
  </form>

  {{-- Tabel --}}
  <div class="card">
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Produk</th>
            <th>Kategori</th>
            <th>SKU</th>
            <th>Stok</th>
            <th>Batas Minimum</th>
            <th>Status</th>
            <th>Terakhir Restok</th>
            <th style="text-align:right">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($items as $item)
            @php
              $product = $item->product;
              $qty     = $item->quantity;
              $thr     = $item->low_stock_threshold;
              $status  = $qty <= 0 ? 'out' : ($qty <= $thr ? 'low' : 'ok');
            @endphp
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:10px">
                  <img class="thumb"
                       src="{{ $product->image_url ?: 'https://placehold.co/80/EFF6FF/2563EB?text=IMG' }}"
                       alt="{{ $product->name }}">
                  <div class="product-name-cell">{{ $product->name }}</div>
                </div>
              </td>
              <td><span class="badge badge-slate">{{ $product->category->name ?? '-' }}</span></td>
              <td><span class="sku-code">{{ $product->sku }}</span></td>
              <td>
                <span style="font-size:15px;font-weight:700;color:var(--charcoal)">{{ $qty }}</span>
                <span style="font-size:12px;color:var(--slate-400)"> unit</span>
              </td>
              <td style="color:var(--slate-400);font-size:13px">{{ $thr }} unit</td>
              <td>
                @if($status === 'out')
                  <span class="badge badge-danger">Habis</span>
                @elseif($status === 'low')
                  <span class="badge badge-amber">Menipis</span>
                @else
                  <span class="badge badge-mint">Aman</span>
                @endif
              </td>
              <td style="color:var(--slate-400);font-size:12.5px">
                {{ $item->last_restocked_at ? $item->last_restocked_at->diffForHumans() : '-' }}
              </td>
              <td style="text-align:right">
                <button class="btn btn-secondary" style="padding:6px 12px;font-size:12px"
                        onclick="openAdjustModal({{ $item->id }}, '{{ addslashes($product->name) }}', {{ $qty }})">
                  Ubah Stok
                </button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @if($items->hasPages())
      <div class="pagination">
        @if($items->onFirstPage())
          <span class="page-link disabled">&#8249;</span>
        @else
          <a class="page-link" href="{{ $items->previousPageUrl() }}">&#8249;</a>
        @endif
        @foreach($items->getUrlRange(max(1, $items->currentPage()-2), min($items->lastPage(), $items->currentPage()+2)) as $page => $url)
          <a class="page-link {{ $page == $items->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
        @endforeach
        @if($items->hasMorePages())
          <a class="page-link" href="{{ $items->nextPageUrl() }}">&#8250;</a>
        @else
          <span class="page-link disabled">&#8250;</span>
        @endif
      </div>
    @endif
  </div>

</div>
@endsection

@push('modals')
<div class="modal-overlay" id="adjustModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Ubah Stok</div>
      <button class="modal-close" onclick="document.getElementById('adjustModal').classList.remove('active')">&#x2715;</button>
    </div>
    <div class="modal-body">
      <div style="margin-bottom:14px">
        <div style="font-size:12px;color:var(--slate-400)">Produk</div>
        <div style="font-size:15px;font-weight:700;color:var(--charcoal)" id="adjustProductName">-</div>
      </div>
      <form id="adjustForm" method="POST">
        @csrf @method('PATCH')
        <div class="form-group">
          <label for="adjustQty">Jumlah Stok Baru</label>
          <input type="number" class="form-control" name="quantity" id="adjustQty" min="0" required>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end">
          <button type="button" class="btn btn-secondary" onclick="document.getElementById('adjustModal').classList.remove('active')">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endpush

@push('scripts')
<script>
  function openAdjustModal(id, name, qty) {
    document.getElementById('adjustProductName').textContent = name;
    document.getElementById('adjustQty').value = qty;
    document.getElementById('adjustForm').action = `/inventory/${id}/adjust`;
    document.getElementById('adjustModal').classList.add('active');
  }
</script>
@endpush
