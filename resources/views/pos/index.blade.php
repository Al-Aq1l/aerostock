@extends('layouts.app')

@section('title', 'Kasir')
@section('breadcrumb', 'Kasir (POS)')
@section('page-title', 'Kasir (POS)')

@section('content')
<div class="pos-layout">

  {{-- ── Area Produk ─────────────────────────────────────────── --}}
  <div class="pos-products-area">

    {{-- Filter Kategori --}}
    <div class="category-tabs">
      <button class="cat-tab active" data-cat="all" onclick="filterCategory(this, 'all')">
        Semua
      </button>
      @foreach($categories as $cat)
        <button class="cat-tab" data-cat="{{ $cat->id }}" onclick="filterCategory(this, '{{ $cat->id }}')">
          {{ $cat->name }}
          <span style="font-size:10px;opacity:0.7">({{ $cat->products_count }})</span>
        </button>
      @endforeach
    </div>

    {{-- Grid Produk --}}
    <div class="product-grid" id="productGrid">
      @foreach($products as $product)
        @php $stock = $product->inventory?->quantity ?? 0; @endphp
        <div class="product-card {{ $stock <= 0 ? 'out-of-stock' : '' }}"
             data-category="{{ $product->category_id }}"
             onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ addslashes($product->image_url ?? '') }}', {{ $stock }})"
             title="{{ $product->name }}">
          <img class="product-card-img"
               src="{{ $product->image_url ?: 'https://placehold.co/300x300/EFF6FF/2563EB?text='.urlencode($product->name) }}"
               alt="{{ $product->name }}"
               loading="lazy">
          <div class="product-card-body">
            <div class="product-card-name">{{ $product->name }}</div>
            <div class="product-card-price">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
            <div class="product-card-stock">
              @if($stock <= 0)
                <span style="color:var(--danger)">Stok habis</span>
              @elseif($stock <= ($product->inventory?->low_stock_threshold ?? 10))
                <span style="color:var(--amber)">{{ $stock }} tersisa</span>
              @else
                <span>{{ $stock }} stok</span>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>

  </div>

  {{-- ── Sidebar Kasir ────────────────────────────────────────── --}}
  <div class="pos-checkout-sidebar">
    <div class="checkout-header">
      <div class="checkout-title">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        Keranjang
      </div>
      <div style="display:flex;align-items:center;gap:8px">
        <span class="checkout-count" id="cartCount">0</span>
        <button class="btn btn-secondary" style="padding:5px 11px;font-size:12px" onclick="clearCart()">Hapus</button>
      </div>
    </div>

    <div class="cart-items" id="cartItems">
      <div class="cart-empty" id="cartEmpty">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        <div style="font-weight:600;color:var(--slate-500);font-size:13px">Keranjang kosong</div>
        <div style="font-size:12px">Klik produk untuk menambahkan</div>
      </div>
    </div>

    <div class="checkout-footer">
      <div class="checkout-line">
        <span>Subtotal</span>
        <span id="subtotalDisplay">Rp0</span>
      </div>
      <div class="checkout-line">
        <span>Pajak (10%)</span>
        <span id="taxDisplay">Rp0</span>
      </div>
      <div class="checkout-total-row">
        <span class="checkout-total-label">Total</span>
        <span class="checkout-total-value" id="totalDisplay">Rp0</span>
      </div>

      {{-- Metode Pembayaran --}}
      <div class="payment-methods">
        <div class="pay-method-btn active" data-method="cash" onclick="selectPayment(this, 'cash')">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="6" width="20" height="12" rx="2"/>
            <circle cx="12" cy="12" r="2"/>
            <path d="M6 12h.01M18 12h.01"/>
          </svg>
          Tunai
        </div>
        <div class="pay-method-btn" data-method="card" onclick="selectPayment(this, 'card')">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="4" width="22" height="16" rx="2"/>
            <line x1="1" y1="10" x2="23" y2="10"/>
          </svg>
          Kartu
        </div>
        <div class="pay-method-btn" data-method="ewallet" onclick="selectPayment(this, 'ewallet')">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="5" y="2" width="14" height="20" rx="2"/>
            <line x1="12" y1="18" x2="12.01" y2="18"/>
          </svg>
          Dompet
        </div>
      </div>

      <button class="btn btn-primary btn-xl" id="checkoutBtn" onclick="processCheckout()" disabled>
        Proses Pembayaran
      </button>
    </div>
  </div>

</div>
@endsection

@push('modals')
<div class="modal-overlay" id="receiptModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Transaksi Berhasil</div>
      <button class="modal-close" onclick="closeReceipt()">&#x2715;</button>
    </div>
    <div class="modal-body receipt-modal">
      <div class="receipt-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
          <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
      </div>
      <div style="font-size:15px;font-weight:700;color:var(--charcoal);margin-bottom:6px">Pembayaran Diterima</div>
      <div class="receipt-ref" id="receiptRef">INV-XXXXXXXX</div>
      <div class="receipt-total-big" id="receiptTotal">Rp0</div>
      <div style="color:var(--slate-400);font-size:12px;margin-top:6px">Terima kasih atas pembelian Anda!</div>
      <div style="display:flex;gap:10px;margin-top:18px;justify-content:center">
        <button class="btn btn-secondary btn-lg" onclick="closeReceipt()">Cetak Struk</button>
        <button class="btn btn-primary btn-lg" onclick="closeReceipt()">Transaksi Baru</button>
      </div>
    </div>
  </div>
</div>
@endpush

@push('scripts')
<script src="{{ asset('js/pos.js') }}"></script>
<script>
  window.CSRF = '{{ csrf_token() }}';
  window.POS_STORE = '{{ route("pos.store") }}';
</script>
@endpush
