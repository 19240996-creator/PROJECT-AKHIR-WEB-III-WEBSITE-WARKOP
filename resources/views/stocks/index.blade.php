@extends('layouts.app')

@section('title', 'Stok Barang')

@section('content')
    <div class="card" style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
        <div>
            <h1>Stok Barang</h1>
            <p class="small">Pantau bahan baku. Notifikasi stok menipis: <strong>{{ $lowStockCount }}</strong> item.</p>
        </div>
        <a href="{{ route('stocks.create') }}" class="btn">+ Tambah Stok</a>
    </div>

    <div class="card">
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
                                <span class="badge" style="background:#ffd7cf;color:#7b1f16;">Menipis</span>
                            @else
                                <span class="badge" style="background:#daf2e3;color:#1c5e3f;">Aman</span>
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
