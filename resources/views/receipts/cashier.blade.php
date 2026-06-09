<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Kasir {{ $order->invoice_number }}</title>
    <style>
        body { font-family: monospace; max-width: 520px; margin: 18px auto; color: #1e1e1e; }
        h1, p { margin: 0; }
        .queue-number { font-size: 44px; font-weight: 700; line-height: 1; margin: 8px 0; }
        .line { border-top: 1px dashed #111; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px 0; border-bottom: 1px dotted #999; text-align: left; }
        .right { text-align: right; }
        .small { font-size: 12px; color: #555; }
        @media print { .no-print { display: none; } body { margin: 0 auto; } }
    </style>
</head>
<body>
    <h1>WARKOP CAPITALIST - STRUK KASIR</h1>
    <p>No Antrian</p>
    <div class="queue-number">A{{ str_pad($order->queue_number, 3, '0', STR_PAD_LEFT) }}</div>
    <p>Invoice: {{ $order->invoice_number }}</p>
    <p>Waktu Order: <span id="order-time">-</span></p>
    <p>Kasir: {{ $order->cashier_name }}</p>
    <p>Customer: {{ $order->customer_name ?: '-' }}</p>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
                <th class="right">Harga</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>
                        {{ $item->menu_name }}
                        @if ($item->menu && $item->menu->stockItem)
                            <div class="small">
                                Potong stok: {{ $item->menu->stock_usage * $item->quantity }} {{ $item->menu->stockItem->unit }} ({{ $item->menu->stockItem->name }})
                            </div>
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td class="right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table>
        <tr><td>Subtotal</td><td class="right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td></tr>
        <tr><td><strong>Total</strong></td><td class="right"><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td></tr>
    </table>

    <p>Metode bayar: {{ strtoupper($order->payment_method) }}</p>

    <p class="no-print" style="margin-top:12px;">
        <button onclick="window.print()">Print</button>
    </p>

    <script>
        function updateOrderTimeRealtime() {
            const now = new Date();
            const formatted = now.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
            });

            const timeNode = document.getElementById('order-time');
            if (timeNode) {
                timeNode.textContent = formatted + ' WIB';
            }
        }

        updateOrderTimeRealtime();
        setInterval(updateOrderTimeRealtime, 1000);
    </script>
</body>
</html>
