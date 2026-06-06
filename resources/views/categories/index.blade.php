@extends('layouts.app')

@section('title', 'Kategori')
@section('breadcrumb', 'Kategori')
@section('page-title', 'Kategori')

@section('content')
<div class="page-body">
  <div class="page-header">
    <div>
      <div class="page-header-title">Kategori Produk</div>
      <div class="page-header-subtitle">Kelola kelompok produk untuk filter katalog dan POS</div>
    </div>
  </div>

  <div class="split-grid">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Tambah Kategori</div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
          @csrf
          <div class="form-group">
            <label for="name">Nama Kategori</label>
            <input class="form-control" id="name" name="name" value="{{ old('name') }}" required>
          </div>
          <button class="btn btn-primary btn-lg" type="submit">Simpan Kategori</button>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>Kategori</th>
              <th>Slug</th>
              <th>Produk</th>
              <th style="text-align:right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($categories as $category)
              <tr>
                <td>
                  <form method="POST" action="{{ route('categories.update', $category) }}" class="inline-edit inline-edit--category">
                    @csrf @method('PUT')
                    <input class="form-control" name="name" value="{{ $category->name }}" required>
                    <button class="btn btn-secondary" type="submit">Simpan</button>
                  </form>
                </td>
                <td><span class="sku-code">{{ $category->slug }}</span></td>
                <td>{{ $category->products_count }}</td>
                <td style="text-align:right">
                  <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-icon" title="Hapus" type="submit">
                      <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/>
                      </svg>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @if($categories->hasPages())
        <div class="pagination">{{ $categories->links() }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
