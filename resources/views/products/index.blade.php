@extends('layouts.app')

@section('title', 'Produk')
@section('breadcrumb', 'Produk')
@section('page-title', 'Produk')

@section('content')
<div class="page-body">

  <div class="page-header">
    <div>
      <div class="page-header-title">Katalog Produk</div>
      <div class="page-header-subtitle">Kelola daftar produk, harga, dan kategori</div>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary btn-lg">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Tambah Produk
    </a>
  </div>

  {{-- Filter --}}
  <form method="GET" action="{{ route('products.index') }}">
    <div class="filters-row">
      <div class="filter-search">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau SKU...">
      </div>
      <select name="category" class="filter-select" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn btn-primary">Cari</button>
      @if(request()->hasAny(['search','category']))
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
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
            <th>Harga Jual</th>
            <th>Harga Beli</th>
            <th>Margin</th>
            <th>Stok</th>
            <th>Status</th>
            <th style="text-align:right">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($products as $product)
            @php
              $margin = $product->margin;
              $stock  = $product->inventory?->quantity ?? 0;
              $ss     = $product->stock_status;
            @endphp
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:10px">
                  <img class="thumb"
                       src="{{ $product->image_url ?: 'https://placehold.co/80/EFF6FF/2563EB?text=IMG' }}"
                       alt="{{ $product->name }}">
                  <div>
                    <div class="product-name-cell">{{ $product->name }}</div>
                    <div class="sku-code">{{ $product->sku }}</div>
                  </div>
                </div>
              </td>
              <td><span class="badge badge-slate">{{ $product->category->name ?? '-' }}</span></td>
              <td style="font-weight:700;color:var(--charcoal)">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
              <td style="color:var(--slate-500)">Rp{{ number_format($product->cost, 0, ',', '.') }}</td>
              <td>
                <span class="badge {{ $margin >= 30 ? 'badge-mint' : ($margin >= 15 ? 'badge-amber' : 'badge-danger') }}">
                  {{ $margin }}%
                </span>
              </td>
              <td style="font-weight:600">{{ $stock }}</td>
              <td>
                @if($ss === 'out')   <span class="badge badge-danger">Habis</span>
                @elseif($ss === 'low') <span class="badge badge-amber">Menipis</span>
                @else                <span class="badge badge-mint">Aman</span>
                @endif
              </td>
              <td style="text-align:right">
                <div style="display:flex;gap:6px;justify-content:flex-end">
                  <a href="{{ route('products.edit', $product) }}" class="btn btn-icon" title="Edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </a>
                  <form method="POST" action="{{ route('products.destroy', $product) }}"
                        onsubmit="return confirm('Hapus produk ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-icon" title="Hapus">
                      <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
                        <path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                      </svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @if($products->hasPages())
      <div class="pagination">
        @if($products->onFirstPage())
          <span class="page-link disabled">&#8249;</span>
        @else
          <a class="page-link" href="{{ $products->previousPageUrl() }}">&#8249;</a>
        @endif
        @foreach($products->getUrlRange(max(1,$products->currentPage()-2), min($products->lastPage(),$products->currentPage()+2)) as $page => $url)
          <a class="page-link {{ $page == $products->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
        @endforeach
        @if($products->hasMorePages())
          <a class="page-link" href="{{ $products->nextPageUrl() }}">&#8250;</a>
        @else
          <span class="page-link disabled">&#8250;</span>
        @endif
      </div>
    @endif
  </div>

</div>
@endsection
