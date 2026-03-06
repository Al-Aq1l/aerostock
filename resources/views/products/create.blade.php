@extends('layouts.app')

@php $isEdit = isset($product); @endphp
@section('title', $isEdit ? 'Edit Produk' : 'Tambah Produk')
@section('breadcrumb', 'Produk')
@section('page-title', $isEdit ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<div class="page-body">

  <div class="page-header">
    <div>
      <div class="page-header-title">{{ $isEdit ? 'Edit Produk' : 'Produk Baru' }}</div>
      <div class="page-header-subtitle">{{ $isEdit ? 'Perbarui informasi dan harga produk' : 'Tambahkan produk baru ke katalog' }}</div>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
      </svg>
      Kembali
    </a>
  </div>

  <div style="display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start">

    {{-- Form Utama --}}
    <div class="card animate-in">
      <div class="card-header">
        <div class="card-title">Informasi Produk</div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ $isEdit ? route('products.update', $product) : route('products.store') }}">
          @csrf
          @if($isEdit) @method('PUT') @endif

          @if($errors->any())
            <div class="flash-msg error">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
              </svg>
              <div>
                Terdapat kesalahan pada form:
                <ul style="margin-top:5px;padding-left:16px">
                  @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
              </div>
            </div>
          @endif

          <div class="form-grid">
            <div class="form-group">
              <label for="name">Nama Produk *</label>
              <input type="text" class="form-control" name="name" id="name"
                     value="{{ old('name', $product->name ?? '') }}"
                     placeholder="Contoh: MacBook Pro 14" required>
            </div>
            <div class="form-group">
              <label for="category_id">Kategori *</label>
              <select class="form-control" name="category_id" id="category_id" required>
                <option value="">Pilih kategori...</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}"
                    {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-grid">
            <div class="form-group">
              <label for="price">Harga Jual (Rp) *</label>
              <input type="number" class="form-control" name="price" id="price"
                     value="{{ old('price', $product->price ?? '') }}"
                     step="1" min="0" placeholder="0" required
                     oninput="calcMargin()">
            </div>
            <div class="form-group">
              <label for="cost">Harga Beli (Rp) *</label>
              <input type="number" class="form-control" name="cost" id="cost"
                     value="{{ old('cost', $product->cost ?? '') }}"
                     step="1" min="0" placeholder="0" required
                     oninput="calcMargin()">
            </div>
          </div>

          {{-- Indikator Margin --}}
          <div id="marginIndicator" style="display:none;margin:-8px 0 16px;padding:9px 14px;border-radius:var(--radius);font-size:13px;font-weight:600">
            Margin Keuntungan: <span id="marginValue">-</span>
          </div>

          @if(!$isEdit)
          <div class="form-grid">
            <div class="form-group">
              <label for="stock">Stok Awal *</label>
              <input type="number" class="form-control" name="stock" id="stock"
                     value="{{ old('stock', 0) }}" min="0" required>
            </div>
            <div class="form-group">
              <label for="threshold">Batas Stok Minimum *</label>
              <input type="number" class="form-control" name="threshold" id="threshold"
                     value="{{ old('threshold', 10) }}" min="0" required>
            </div>
          </div>
          @else
          <div class="form-group">
            <label for="threshold">Batas Stok Minimum *</label>
            <input type="number" class="form-control" name="threshold" id="threshold"
                   value="{{ old('threshold', $product->inventory?->low_stock_threshold ?? 10) }}" min="0" required>
          </div>
          @endif

          <div class="form-group">
            <label for="image_url">URL Gambar</label>
            <input type="url" class="form-control" name="image_url" id="image_url"
                   value="{{ old('image_url', $product->image_url ?? '') }}"
                   placeholder="https://..." oninput="previewImage(this.value)">
          </div>

          <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea class="form-control" name="description" id="description" rows="3"
                      placeholder="Deskripsi singkat produk...">{{ old('description', $product->description ?? '') }}</textarea>
          </div>

          <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:6px">
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg">Batal</a>
            <button type="submit" class="btn btn-primary btn-lg">
              {{ $isEdit ? 'Simpan Perubahan' : 'Buat Produk' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Panel Preview --}}
    <div style="position:sticky;top:80px">
      <div class="card animate-in" style="animation-delay:0.1s">
        <div class="card-header">
          <div class="card-title">Pratinjau</div>
        </div>
        <div class="card-body" style="text-align:center">
          <img id="previewImg"
               src="{{ $product->image_url ?? 'https://placehold.co/300/EFF6FF/2563EB?text=Gambar' }}"
               alt="Pratinjau"
               style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:var(--radius);margin-bottom:12px;background:var(--surface-2)">
          <div style="font-size:14px;font-weight:700;color:var(--charcoal)">{{ $product->name ?? 'Nama Produk' }}</div>
          <div style="font-size:18px;font-weight:800;color:var(--accent);margin-top:4px">
            Rp{{ number_format($product->price ?? 0, 0, ',', '.') }}
          </div>
        </div>
      </div>

      @if($isEdit)
        <div class="card animate-in" style="margin-top:14px;animation-delay:0.15s">
          <div class="card-body">
            <div style="font-size:11px;font-weight:600;color:var(--slate-400);letter-spacing:0.7px;text-transform:uppercase;margin-bottom:10px">Info Stok</div>
            <div class="checkout-line">
              <span>Stok Saat Ini</span>
              <strong>{{ $product->inventory?->quantity ?? 0 }}</strong>
            </div>
            <div class="checkout-line">
              <span>Batas Minimum</span>
              <strong>{{ $product->inventory?->low_stock_threshold ?? 10 }}</strong>
            </div>
            <div class="checkout-line" style="margin-top:6px">
              <span>Status</span>
              @php $ss = $product->stock_status; @endphp
              <span class="badge badge-{{ $ss === 'ok' ? 'mint' : ($ss === 'low' ? 'amber' : 'danger') }}">
                {{ $ss === 'ok' ? 'Aman' : ($ss === 'low' ? 'Menipis' : 'Habis') }}
              </span>
            </div>
          </div>
        </div>
      @endif
    </div>

  </div>

</div>
@endsection

@push('scripts')
<script>
  function previewImage(url) {
    const img = document.getElementById('previewImg');
    if(url) {
      img.src = url;
      img.onerror = () => img.src = 'https://placehold.co/300/EFF6FF/2563EB?text=URL+Tidak+Valid';
    }
  }

  function calcMargin() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const cost  = parseFloat(document.getElementById('cost').value)  || 0;
    const el    = document.getElementById('marginIndicator');
    const val   = document.getElementById('marginValue');
    if(price > 0 && cost >= 0) {
      const margin = ((price - cost) / price) * 100;
      el.style.display = 'block';
      val.textContent  = margin.toFixed(1) + '%';
      if(margin >= 30) {
        el.style.background = 'var(--mint-light)'; el.style.color = '#065F46';
      } else if(margin >= 15) {
        el.style.background = 'var(--amber-light)'; el.style.color = '#92400E';
      } else {
        el.style.background = 'var(--danger-light)'; el.style.color = '#991B1B';
      }
    } else {
      el.style.display = 'none';
    }
  }
  calcMargin();
</script>
@endpush
