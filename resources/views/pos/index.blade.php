@extends(auth()->user()?->role === 'cashier' ? 'layouts.pos' : 'layouts.app')

@section('title', auth()->user()?->role === 'cashier' ? 'Kasir' : 'Pantau POS')
@section('breadcrumb', auth()->user()?->role === 'cashier' ? 'Kasir (POS)' : 'Pantau POS')
@section('page-title', auth()->user()?->role === 'cashier' ? 'Kasir (POS)' : 'Pantau POS')

@section('content')
@php
  $isCashier = auth()->user()?->role === 'cashier';
  $availableProducts = $products->count();
  $totalStock = $products->sum(fn ($product) => $product->inventory?->quantity ?? 0);
  $lowStockProducts = $products->filter(fn ($product) => ($product->inventory?->quantity ?? 0) <= ($product->inventory?->low_stock_threshold ?? 10))->count();
@endphp

@unless($isCashier)
<div class="page-body owner-pos-body">
@endunless

<div class="pos-layout {{ $isCashier ? '' : 'pos-layout--embedded pos-layout--readonly' }}">

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
        <div class="product-card {{ $stock <= 0 ? 'out-of-stock' : '' }} {{ $isCashier ? '' : 'product-card--readonly' }}"
             data-id="{{ $product->id }}"
             data-category="{{ $product->category_id }}"
             data-name="{{ $product->name }}"
             data-price="{{ $product->price }}"
             data-image="{{ $product->image_url ?? '' }}"
             data-stock="{{ $stock }}"
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

  @if($isCashier)
    <button class="mobile-cart-fab" type="button" onclick="toggleMobileCart()" aria-label="Buka keranjang">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
      </svg>
      <span class="mobile-cart-fab-count" id="mobileCartCount">0</span>
      <span class="mobile-cart-fab-total" id="mobileCartTotal">Rp0</span>
    </button>

    <div class="mobile-cart-backdrop" onclick="closeMobileCart()"></div>
  @endif

  {{-- ── Sidebar Kasir ────────────────────────────────────────── --}}
  @if($isCashier)
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
        <button class="mobile-cart-close" type="button" onclick="closeMobileCart()" aria-label="Tutup keranjang">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
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
          QRIS
        </div>
      </div>

      <button class="btn btn-primary btn-xl" id="checkoutBtn" onclick="processCheckout()" disabled>
        Proses Pembayaran
      </button>
    </div>
  </div>
  @else
  <aside class="pos-checkout-sidebar pos-monitor-sidebar">
    <div class="checkout-header">
      <div class="checkout-title">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 3v18h18"/>
          <path d="M7 15l4-4 3 3 5-7"/>
        </svg>
        Pantauan POS
      </div>
      <span class="checkout-count">Admin</span>
    </div>

    <div class="pos-monitor-body">
      <div class="pos-monitor-stat">
        <span>Produk tersedia</span>
        <strong>{{ $availableProducts }}</strong>
      </div>
      <div class="pos-monitor-stat">
        <span>Total stok tampil</span>
        <strong>{{ number_format($totalStock, 0, ',', '.') }}</strong>
      </div>
      <div class="pos-monitor-stat">
        <span>Stok menipis</span>
        <strong>{{ $lowStockProducts }}</strong>
      </div>
      <div class="pos-monitor-note">
        Mode pantau admin
      </div>
    </div>
  </aside>
  @endif

</div>

@unless($isCashier)
</div>
@endunless
@endsection

@if($isCashier)
@push('modals')
<div class="modal-overlay" id="receiptModal" onclick="closeReceipt()">
  <div class="modal receipt-preview-modal" onclick="event.stopPropagation()">
    <div class="modal-header">
      <div>
        <div class="receipt-success-heading">
          <span class="receipt-success-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </span>
          <div>
            <div class="modal-title">Transaksi Berhasil</div>
            <div style="font-size:12px;color:var(--slate-400);margin-top:2px">Preview struk sebelum dicetak</div>
          </div>
        </div>
      </div>
      <button class="modal-close" onclick="closeReceipt()">&#x2715;</button>
    </div>
    <div class="modal-body receipt-modal">
      <div class="receipt-success-banner">
        Transaksi berhasil.
      </div>

      <div class="receipt-paper" id="receiptPrintArea">
        <div class="receipt-brand">AeroStock</div>
        <div class="receipt-muted">Struk Pembayaran</div>
        <div class="receipt-ref" id="receiptRef">INV-XXXXXXXX</div>

        <div class="receipt-meta">
          <div>
            <span>Tanggal</span>
            <strong id="receiptDate">-</strong>
          </div>
          <div>
            <span>Pembayaran</span>
            <strong id="receiptMethod">-</strong>
          </div>
        </div>

        <div class="receipt-items" id="receiptItems"></div>

        <div class="receipt-summary">
          <div>
            <span>Subtotal</span>
            <strong id="receiptSubtotal">Rp0</strong>
          </div>
          <div>
            <span>Pajak (10%)</span>
            <strong id="receiptTax">Rp0</strong>
          </div>
          <div class="receipt-grand-total">
            <span>Total</span>
            <strong id="receiptTotal">Rp0</strong>
          </div>
        </div>

        <div class="receipt-thanks">Terima kasih atas pembelian Anda.</div>
      </div>

      <div class="receipt-actions">
        <button class="btn btn-secondary btn-lg" onclick="printReceipt()">Cetak Struk</button>
        <button class="btn btn-primary btn-lg" onclick="closeReceipt()">Transaksi Baru</button>
      </div>
    </div>
  </div>
</div>
@endpush
@endif

@push('scripts')
<script>
  window.POS_READ_ONLY = @json(! $isCashier);
  window.CSRF = '{{ csrf_token() }}';
  @if($isCashier)
    window.POS_STORE = '{{ route("pos.store") }}';
  @endif
</script>
<script src="{{ asset('js/pos.js') }}?v={{ filemtime(public_path('js/pos.js')) }}"></script>
@endpush
