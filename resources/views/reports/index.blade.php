@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('header_title', 'Laporan Penjualan')

@section('content')
<div class="content-body" style="padding: 24px">
  
  <div class="card" style="margin-bottom: 20px">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
      <h3 style="font-size: 16px; font-weight: 700;">Riwayat Transaksi</h3>
    </div>
    <div class="card-body">
      @if($sales->isEmpty())
        <div style="text-align:center; padding: 40px; color: var(--slate-400)">
          Belum ada data transaksi.
        </div>
      @else
        <div class="table-container">
          <table class="table">
            <thead>
              <tr>
                <th>No. Referensi</th>
                <th>Tanggal</th>
                <th>Pembayaran</th>
                <th>Item Terjual</th>
                <th>Total</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($sales as $sale)
              <tr>
                <td style="font-weight: 600; color: var(--accent)">{{ $sale->reference }}</td>
                <td>{{ $sale->created_at->format('d M Y, H:i') }}</td>
                <td>
                  <span style="font-size:12px; font-weight:600; background:var(--surface-2); padding:4px 8px; border-radius:4px; border:1px solid var(--border)">
                    {{ ucfirst($sale->payment_method) }}
                  </span>
                </td>
                <td>
                  <div style="font-size:13px; color:var(--slate-500)">
                    {{ $sale->items->sum('quantity') }} item
                    @if($sale->items->count() > 0)
                      <div style="font-size:11px; opacity:0.8; margin-top:2px">
                        {{ $sale->items->first()->product->name ?? 'Produk dihapus' }}
                        @if($sale->items->count() > 1)
                          (+{{ $sale->items->count() - 1 }} lainnya)
                        @endif
                      </div>
                    @endif
                  </div>
                </td>
                <td style="font-weight: 700; color: var(--charcoal)">
                  Rp{{ number_format($sale->total, 0, ',', '.') }}
                </td>
                <td>
                  <span style="color:var(--mint); font-size:12px; font-weight:600; background:var(--mint-light); padding:4px 8px; border-radius:4px">
                    Selesai
                  </span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        
        <div style="margin-top: 20px">
          {{ $sales->links() }}
        </div>
      @endif
    </div>
  </div>

</div>
@endsection
