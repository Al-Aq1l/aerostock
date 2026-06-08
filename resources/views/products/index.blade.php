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
              <td>{{ $product->category->name ?? '-' }}</td>
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
                <div class="action-dropdown">
                  <button class="btn btn-secondary action-trigger" type="button" onclick="toggleActionMenu(this)">
                    Aksi
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="6 9 12 15 18 9"/>
                    </svg>
                  </button>
                  <div class="action-menu">
                    <button type="button"
                            class="action-menu-item"
                            data-detail-id="product-detail-{{ $product->id }}"
                            onclick="openProductDetail(this)">
                      Detail
                    </button>
                    <script type="application/json" id="product-detail-{{ $product->id }}">
                      {!! json_encode([
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'image' => $product->image_url ?: 'https://placehold.co/600x420/EFF6FF/2563EB?text=' . urlencode($product->name),
                        'category' => $product->category->name ?? '-',
                        'price' => 'Rp' . number_format($product->price, 0, ',', '.'),
                        'cost' => 'Rp' . number_format($product->cost, 0, ',', '.'),
                        'margin' => $margin . '%',
                        'stock' => $stock . ' unit',
                        'status' => $ss === 'out' ? 'Habis' : ($ss === 'low' ? 'Menipis' : 'Aman'),
                        'description' => $product->description ?: '-',
                      ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) !!}
                    </script>
                    <a href="{{ route('products.edit', $product) }}" class="action-menu-item">Edit</a>
                    <form method="POST" action="{{ route('products.destroy', $product) }}"
                          onsubmit="return confirm('Hapus produk ini?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="action-menu-item danger">Hapus</button>
                    </form>
                  </div>
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

@push('modals')
<div class="modal-overlay" id="productDetailModal">
  <div class="modal">
    <div class="modal-header">
      <div>
        <div class="modal-title" id="detailProductName">Detail Produk</div>
        <div style="font-size:12px;color:var(--slate-400);margin-top:2px" id="detailProductSku">-</div>
      </div>
      <button class="modal-close" type="button" onclick="closeProductDetail()">&#x2715;</button>
    </div>
    <div class="modal-body">
      <img class="detail-image" id="detailProductImage" src="" alt="Gambar produk">
      <div class="detail-grid">
        <div><span>Kategori</span><strong id="detailCategory">-</strong></div>
        <div><span>Harga Jual</span><strong id="detailPrice">-</strong></div>
        <div><span>Harga Beli</span><strong id="detailCost">-</strong></div>
        <div><span>Margin</span><strong id="detailMargin">-</strong></div>
        <div><span>Stok</span><strong id="detailStock">-</strong></div>
        <div><span>Status</span><strong id="detailStatus">-</strong></div>
      </div>
      <div class="detail-note">
        <span>Deskripsi</span>
        <p id="detailDescription">-</p>
      </div>
    </div>
  </div>
</div>
@endpush

@push('scripts')
<script>
  function toggleActionMenu(button) {
    const dropdown = button.closest('.action-dropdown');
    const menu = dropdown.querySelector('.action-menu');
    document.querySelectorAll('.action-dropdown.open').forEach((item) => {
      if (item !== dropdown) item.classList.remove('open');
    });
    dropdown.classList.toggle('open');

    if (dropdown.classList.contains('open')) {
      const rect = button.getBoundingClientRect();
      const menuWidth = menu.offsetWidth || 132;
      menu.style.top = `${rect.bottom + 6}px`;
      menu.style.left = `${Math.max(12, rect.right - menuWidth)}px`;
    }
  }

  function openProductDetail(button) {
    const detail = JSON.parse(document.getElementById(button.dataset.detailId).textContent);
    document.getElementById('detailProductName').textContent = detail.name;
    document.getElementById('detailProductSku').textContent = detail.sku;
    document.getElementById('detailProductImage').src = detail.image;
    document.getElementById('detailProductImage').alt = detail.name;
    document.getElementById('detailCategory').textContent = detail.category;
    document.getElementById('detailPrice').textContent = detail.price;
    document.getElementById('detailCost').textContent = detail.cost;
    document.getElementById('detailMargin').textContent = detail.margin;
    document.getElementById('detailStock').textContent = detail.stock;
    document.getElementById('detailStatus').textContent = detail.status;
    document.getElementById('detailDescription').textContent = detail.description;
    document.querySelectorAll('.action-dropdown.open').forEach((item) => item.classList.remove('open'));
    document.body.classList.add('modal-scroll-lock');
    document.getElementById('productDetailModal').classList.add('active');
  }

  function closeProductDetail() {
    document.getElementById('productDetailModal').classList.remove('active');
    document.body.classList.remove('modal-scroll-lock');
  }

  document.addEventListener('click', (event) => {
    if (!event.target.closest('.action-dropdown')) {
      document.querySelectorAll('.action-dropdown.open').forEach((item) => item.classList.remove('open'));
    }

    if (event.target.id === 'productDetailModal') {
      closeProductDetail();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeProductDetail();
    }
  });
</script>
@endpush
