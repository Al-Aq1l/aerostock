@extends('layouts.app')

@section('title', 'Supplier')
@section('breadcrumb', 'Supplier')
@section('page-title', 'Supplier')

@section('content')
<div class="page-body">
  <div class="page-header">
    <div>
      <div class="page-header-title">Supplier</div>
      <div class="page-header-subtitle">Kelola pemasok untuk produk dan stok toko</div>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-lg">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Tambah Supplier
    </a>
  </div>

  <form method="GET" action="{{ route('suppliers.index') }}">
    <div class="filters-row">
      <div class="filter-search">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari supplier, kontak, atau telepon...">
      </div>
      <button type="submit" class="btn btn-primary">Cari</button>
      @if(request('search'))
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Reset</a>
      @endif
    </div>
  </form>

  <div class="card">
    <div class="table-wrapper suppliers-table-wrapper">
      <table class="suppliers-table">
        <thead>
          <tr>
            <th>Supplier</th>
            <th>Kontak</th>
            <th>Email</th>
            <th>Produk</th>
            <th>Status</th>
            <th style="text-align:right">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($suppliers as $supplier)
            <tr>
              <td>
                <div class="product-name-cell">{{ $supplier->name }}</div>
                <div class="sku-code">{{ $supplier->address ?: '-' }}</div>
              </td>
              <td>
                <div>{{ $supplier->contact_name ?: '-' }}</div>
                <div class="sku-code">{{ $supplier->phone ?: '-' }}</div>
              </td>
              <td>{{ $supplier->email ?: '-' }}</td>
              <td>{{ $supplier->products_count }}</td>
              <td>
                <span class="badge {{ $supplier->is_active ? 'badge-mint' : 'badge-slate' }}">
                  {{ $supplier->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td style="text-align:right">
                <div style="display:flex;gap:6px;justify-content:flex-end">
                  <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-icon" title="Edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </a>
                  <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" onsubmit="return confirm('Hapus supplier ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-icon" title="Hapus">
                      <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/>
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

    @if($suppliers->hasPages())
      <div class="pagination">{{ $suppliers->links() }}</div>
    @endif
  </div>
</div>
@endsection
