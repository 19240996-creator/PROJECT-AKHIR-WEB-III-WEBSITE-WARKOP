@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="card">
        <h1>Riwayat Pemesanan</h1>
        <p class="small">Filter laporan harian, mingguan, dan bulanan.</p>

        <form method="GET" action="{{ route('reports.index') }}" style="display:flex;gap:8px;align-items:end;flex-wrap:wrap;">
            <div>
                <label>Periode</label>
                <select class="input" name="period">
                    <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Harian</option>
                    <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                    <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                </select>
            </div>
            <button class="btn" type="submit">Terapkan</button>
            <a class="btn btn-muted" href="{{ route('reports.export', ['period' => $period]) }}">Export Excel (CSV)</a>
        </form>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <h3>Ringkasan</h3>
            <p>Total Order: <strong>{{ $summary['order_count'] }}</strong></p>
            <p>Omzet Kotor: <strong>Rp {{ number_format($summary['gross_revenue'], 0, ',', '.') }}</strong></p>
            <p>Omzet Bersih: <strong>Rp {{ number_format($summary['net_revenue'], 0, ',', '.') }}</strong></p>
        </div>
    </div>

    <div class="card">
        <h2>Data Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Waktu</th>
                    <th>Customer</th>
                    <th>Kasir</th>
                    <th>Pembayaran</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->invoice_number }}</td>
                        <td>{{ optional($order->ordered_at)->format('d-m-Y H:i:s') }} WIB</td>
                        <td>{{ $order->customer_name ?: '-' }}</td>
                        <td>{{ $order->cashier_name }}</td>
                        <td>{{ strtoupper($order->payment_method) }}</td>
                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>
                            <a class="btn btn-muted" href="{{ route('cashier.show', $order) }}">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="small">Belum ada data transaksi untuk periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:12px;">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
