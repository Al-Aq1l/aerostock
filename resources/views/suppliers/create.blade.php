@extends('layouts.app')

@php $isEdit = isset($supplier); @endphp
@section('title', $isEdit ? 'Edit Supplier' : 'Tambah Supplier')
@section('breadcrumb', 'Supplier')
@section('page-title', $isEdit ? 'Edit Supplier' : 'Tambah Supplier')

@section('content')
<div class="page-body">
  <div class="page-header">
    <div>
      <div class="page-header-title">{{ $isEdit ? 'Edit Supplier' : 'Supplier Baru' }}</div>
      <div class="page-header-subtitle">Simpan informasi pemasok agar pembelian stok lebih mudah dilacak</div>
    </div>
    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
      </svg>
      Kembali
    </a>
  </div>

  <div class="card narrow-card">
    <div class="card-header">
      <div class="card-title">Informasi Supplier</div>
    </div>
    <div class="card-body">
      @if($errors->any())
        <div class="flash-msg error">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ $isEdit ? route('suppliers.update', $supplier) : route('suppliers.store') }}">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="form-group">
          <label for="name">Nama Supplier *</label>
          <input class="form-control" id="name" name="name" value="{{ old('name', $supplier->name ?? '') }}" required>
        </div>

        <div class="form-grid">
          <div class="form-group">
            <label for="contact_name">Nama Kontak</label>
            <input class="form-control" id="contact_name" name="contact_name" value="{{ old('contact_name', $supplier->contact_name ?? '') }}">
          </div>
          <div class="form-group">
            <label for="phone">Telepon</label>
            <input class="form-control" id="phone" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}">
          </div>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $supplier->email ?? '') }}">
        </div>

        <div class="form-group">
          <label for="address">Alamat</label>
          <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $supplier->address ?? '') }}</textarea>
        </div>

        <label class="check-row">
          <input type="checkbox" name="is_active" value="1" {{ old('is_active', $supplier->is_active ?? true) ? 'checked' : '' }}>
          <span>Supplier aktif</span>
        </label>

        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:14px">
          <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-lg">Batal</a>
          <button class="btn btn-primary btn-lg" type="submit">{{ $isEdit ? 'Simpan Perubahan' : 'Buat Supplier' }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
