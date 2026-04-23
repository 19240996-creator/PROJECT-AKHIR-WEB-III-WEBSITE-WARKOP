@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <section class="card report-hero">
        <div>
            <div class="hero-eyebrow">Laporan</div>
            <h1>Riwayat pemesanan dan performa penjualan</h1>
            <p class="small">Filter laporan harian, mingguan, atau bulanan untuk cek performa terbaru.</p>
            <form method="GET" action="{{ route('reports.index') }}" class="report-filters">
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
        <div>
            <span class="chip">Periode aktif: {{ strtoupper($period) }}</span>
            <div class="summary-grid" style="margin-top:12px;">
                <div class="summary-card">
                    <span class="small">Total Order</span>
                    <strong>{{ $summary['order_count'] }}</strong>
                </div>
                <div class="summary-card">
                    <span class="small">Omzet Kotor</span>
                    <strong>Rp {{ number_format($summary['gross_revenue'], 0, ',', '.') }}</strong>
                </div>
                <div class="summary-card">
                    <span class="small">Omzet Bersih</span>
                    <strong>Rp {{ number_format($summary['net_revenue'], 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </section>

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
                        <td><span class="payment-pill">{{ strtoupper($order->payment_method) }}</span></td>
                        <td><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
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
