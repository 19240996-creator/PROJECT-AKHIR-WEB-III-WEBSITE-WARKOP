@extends('layouts.app')

@section('title', 'Stok Barang')

@section('content')
    <section class="card stock-hero">
        <div>
            <div class="hero-eyebrow">Stok Barang</div>
            <h1>Kontrol bahan baku lebih mudah</h1>
            <p class="small">Pantau stok, catatan, dan status menipis agar operasional tetap lancar.</p>
            <div class="hero-actions">
                <a href="{{ route('stocks.create') }}" class="btn">+ Tambah Stok</a>
                <a href="#daftar-stok" class="btn btn-muted">Lihat Daftar</a>
            </div>
        </div>
        <div>
            <span class="chip">{{ $lowStockCount }} item menipis</span>
            <div class="stock-kpis">
                <div class="stock-kpi">
                    <span class="small">Total Item</span>
                    <strong>{{ $stockItems->total() }}</strong>
                </div>
                <div class="stock-kpi">
                    <span class="small">Status Aman</span>
                    <strong>{{ max($stockItems->total() - $lowStockCount, 0) }}</strong>
                </div>
                <div class="stock-kpi">
                    <span class="small">Butuh Tindak</span>
                    <strong>{{ $lowStockCount }}</strong>
                </div>
            </div>
        </div>
    </section>

    <div class="card" id="daftar-stok">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Qty</th>
                    <th>Minimum</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stockItems as $stock)
                    <tr>
                        <td>{{ $stock->name }} <span class="small">({{ $stock->unit }})</span></td>
                        <td>{{ number_format($stock->quantity, 2, ',', '.') }}</td>
                        <td>{{ number_format($stock->minimum_quantity, 2, ',', '.') }}</td>
                        <td>
                            @if ($stock->quantity <= $stock->minimum_quantity)
                                <span class="status-badge status-low">Menipis</span>
                            @else
                                <span class="status-badge status-ok">Aman</span>
                            @endif
                        </td>
                        <td>{{ $stock->note ?: '-' }}</td>
                        <td>
                            <a class="btn btn-muted" href="{{ route('stocks.edit', $stock) }}">Edit</a>
                            <form action="{{ route('stocks.destroy', $stock) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit" onclick="return confirm('Hapus item stok ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="small">Belum ada data stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:12px;">
            {{ $stockItems->links() }}
        </div>
    </div>
@endsection
