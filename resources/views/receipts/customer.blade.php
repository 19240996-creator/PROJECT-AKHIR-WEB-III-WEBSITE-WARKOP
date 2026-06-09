<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Customer {{ $order->invoice_number }}</title>
    <style>
        body { font-family: monospace; max-width: 360px; margin: 18px auto; color: #1e1e1e; }
        h1, p { margin: 0; }
        .center { text-align: center; }
        .queue-number { font-size: 44px; font-weight: 700; line-height: 1; margin: 8px 0; }
        .line { border-top: 1px dashed #111; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 4px 0; vertical-align: top; }
        .right { text-align: right; }
        @media print { .no-print { display: none; } body { margin: 0 auto; } }
    </style>
</head>
<body>
    <div class="center">
        <h1>WARKOP CAPITALIST</h1>
        <p>Terima kasih sudah mampir</p>
    </div>

    <div class="line"></div>

    <div class="center">
        <p>No Antrian</p>
        <div class="queue-number">A{{ str_pad($order->queue_number, 3, '0', STR_PAD_LEFT) }}</div>
    </div>
    <p>Invoice: {{ $order->invoice_number }}</p>
    <p>Waktu Order: <span id="order-time">-</span></p>
    <p>Cust   : {{ $order->customer_name ?: '-' }}</p>

    <div class="line"></div>

    <table>
        @foreach ($order->items as $item)
            <tr>
                <td colspan="2">{{ $item->menu_name }} x{{ $item->quantity }}</td>
            </tr>
            <tr>
                <td class="right" colspan="2">Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td>Subtotal</td>
            <td class="right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td class="right"><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center">
        <p>Metode: {{ strtoupper($order->payment_method) }}</p>
        <p>Kasir: {{ $order->cashier_name }}</p>
        <p>Sampai jumpa lagi!</p>
    </div>

    <p class="no-print center" style="margin-top:12px;">
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
