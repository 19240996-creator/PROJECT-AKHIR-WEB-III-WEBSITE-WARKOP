@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
    <div class="card">
        <h1>Detail Pesanan</h1>
        <div class="grid grid-2">
            <div>
                <p><strong>No Antrian:</strong> A{{ str_pad($order->queue_number, 3, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Invoice:</strong> {{ $order->invoice_number }}</p>
                <p><strong>Waktu:</strong> {{ optional($order->ordered_at)->format('d-m-Y H:i:s') }} WIB</p>
                <p><strong>Customer:</strong> {{ $order->customer_name ?: '-' }}</p>
            </div>
            <div>
                <p><strong>Kasir:</strong> {{ $order->cashier_name }}</p>
                <p><strong>Pembayaran:</strong> {{ strtoupper($order->payment_method) }}</p>
                <span class="badge">Total Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Item Pesanan</h2>
        <table>
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->menu_name }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:12px; text-align:right;">
            <p>Subtotal: Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
            <p><strong>Total: Rp {{ number_format($order->total, 0, ',', '.') }}</strong></p>
        </div>

        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a class="btn" target="_blank" href="{{ route('receipts.customer', $order) }}">Cetak Struk Customer</a>
            <a class="btn btn-muted" target="_blank" href="{{ route('receipts.cashier', $order) }}">Cetak Struk Kasir</a>
        </div>
    </div>
@endsection
