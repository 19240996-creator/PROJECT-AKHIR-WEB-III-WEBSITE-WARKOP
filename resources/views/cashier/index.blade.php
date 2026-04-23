@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
    <section class="card cashier-hero">
        <div>
            <div class="hero-eyebrow">Kasir Warkop</div>
            <h1>Transaksi cepat, rapi, dan siap cetak</h1>
            <p class="small">Buat pesanan baru, pantau menu aktif, lalu cetak struk pelanggan dan kasir.</p>
            <div class="hero-actions">
                <a class="btn" href="#transaksi-baru">Mulai Transaksi</a>
                <a class="btn btn-muted" href="#menu-aktif">Cek Menu Aktif</a>
                <a class="btn btn-muted" href="#riwayat">Riwayat Terbaru</a>
            </div>
        </div>
        <div>
            <div class="chip">Siap melayani pelanggan</div>
            <div class="quick-stats">
                <div class="quick-stat">
                    <span class="small">Menu Aktif</span>
                    <strong>{{ $menus->count() }}</strong>
                </div>
                <div class="quick-stat">
                    <span class="small">Riwayat Tampil</span>
                    <strong>{{ $recentOrders->count() }}</strong>
                </div>
                <div class="quick-stat">
                    <span class="small">Metode</span>
                    <strong>Cash & QRIS</strong>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-2" id="transaksi-baru">
        <section class="card">
            <div class="panel-title">
                <h2>Transaksi Baru</h2>
                <span class="badge">Siap diproses</span>
            </div>
            <form action="{{ route('cashier.store') }}" method="POST" id="order-form">
                @csrf
                <input type="hidden" name="client_ordered_at" id="client_ordered_at">

                <div class="form-grid" style="margin-bottom:10px;">
                    <div>
                        <label>Nama Customer</label>
                        <input class="input" type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Contoh: Meja 7" required>
                    </div>
                    <div>
                        <label>Nama Kasir</label>
                        <input class="input" type="text" name="cashier_name" value="{{ old('cashier_name', 'Kasir Warkop') }}">
                    </div>
                    <div>
                        <label>Metode Pembayaran</label>
                        <select name="payment_method" class="input">
                            <option value="cash">Cash</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                </div>

                <h3>Item Pesanan</h3>
                <div id="item-list">
                    <div class="item-row">
                        <div>
                            <label>Menu</label>
                            <select class="input" name="items[0][menu_id]" required>
                                <option value="">Pilih menu</option>
                                @foreach ($menus as $menu)
                                    <option value="{{ $menu->id }}">
                                        {{ $menu->name }} - Rp {{ number_format($menu->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Qty</label>
                            <input class="input" type="number" name="items[0][quantity]" min="1" value="1" required>
                        </div>
                        <button type="button" class="btn btn-danger" onclick="removeRow(this)">Hapus</button>
                    </div>
                </div>

                <div class="section-divider"></div>

                <button type="button" class="btn btn-muted" onclick="addRow()">+ Tambah Item</button>

                <div style="margin-top:12px;">
                    <button class="btn" type="submit">Proses Pesanan</button>
                </div>
            </form>
        </section>

        <section class="card" id="menu-aktif">
            <h2>Daftar Menu Aktif</h2>
            <table>
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Harga</th>
                        <th>Stok Terkait</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menus as $menu)
                        <tr>
                            <td>
                                <strong>{{ $menu->name }}</strong><br>
                                <span class="small">{{ $menu->description }}</span>
                            </td>
                            <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td>
                                @if ($menu->stockItem)
                                    {{ $menu->stockItem->name }} ({{ number_format($menu->stockItem->quantity, 2, ',', '.') }} {{ $menu->stockItem->unit }})
                                @else
                                    <span class="small">Tidak terhubung</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="small">Belum ada menu aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>

    <section class="card" id="riwayat">
        <div class="panel-title">
            <h2>Riwayat Transaksi Terbaru</h2>
            <form action="{{ route('cashier.recent.reset') }}" method="POST" onsubmit="return confirm('Reset riwayat transaksi terbaru? Data laporan tidak akan dihapus.');">
                @csrf
                <button type="submit" class="btn btn-muted">Reset Riwayat Terbaru</button>
            </form>
        </div>

        @if (!empty($recentResetAt))
            <p class="small" style="margin:0 0 10px;">Riwayat terbaru sedang dihitung ulang sejak: {{ \Illuminate\Support\Carbon::parse($recentResetAt)->format('d-m-Y H:i:s') }} WIB.</p>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Waktu</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentOrders as $order)
                    <tr>
                        <td>{{ $order->invoice_number }}</td>
                        <td>{{ optional($order->ordered_at)->format('d-m-Y H:i:s') }} WIB</td>
                        <td>{{ $order->customer_name ?: '-' }}</td>
                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td><a class="btn btn-muted" href="{{ route('cashier.show', $order) }}">Detail</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="small">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <script>
        let rowIndex = 1;

        function addRow() {
            const list = document.getElementById('item-list');
            const template = document.querySelector('.item-row').cloneNode(true);

            template.querySelector('select').name = `items[${rowIndex}][menu_id]`;
            template.querySelector('select').value = '';
            template.querySelector('input').name = `items[${rowIndex}][quantity]`;
            template.querySelector('input').value = 1;

            list.appendChild(template);
            rowIndex++;
        }

        function removeRow(button) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length <= 1) {
                alert('Minimal 1 item pesanan.');
                return;
            }

            button.closest('.item-row').remove();
        }

        document.getElementById('order-form').addEventListener('submit', function() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hour = String(now.getHours()).padStart(2, '0');
            const minute = String(now.getMinutes()).padStart(2, '0');
            const second = String(now.getSeconds()).padStart(2, '0');

            document.getElementById('client_ordered_at').value = `${year}-${month}-${day} ${hour}:${minute}:${second}`;
        });
    </script>
@endsection
