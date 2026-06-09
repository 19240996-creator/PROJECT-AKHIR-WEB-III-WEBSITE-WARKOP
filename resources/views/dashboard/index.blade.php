@extends('layouts.app')

@section('title', 'Dashboard Insight')

@section('content')
    <div class="card">
        <h1>Dashboard Insight</h1>
        <p class="small">Ringkasan cepat pendapatan dan menu terlaris Warkop Capitalist.</p>
    </div>

    <section class="card">
        <h2>Statistik Pendapatan</h2>
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); margin-top:10px;">
            <div style="padding:14px;border:1px solid #dbe4f7;border-radius:14px;background:#f8fbff;">
                <p class="small" style="margin:0 0 6px;"><strong>Harian</strong></p>
                <p style="margin:0 0 6px;">Pendapatan: <strong>Rp {{ number_format($stats['daily']['revenue'], 0, ',', '.') }}</strong></p>
                <p class="small" style="margin:0;">Order: {{ $stats['daily']['order_count'] }}</p>
                <p class="small" style="margin:6px 0 0;">{{ $stats['daily']['start']->format('d-m-Y') }}</p>
            </div>

            <div style="padding:14px;border:1px solid #dbe4f7;border-radius:14px;background:#f8fbff;">
                <p class="small" style="margin:0 0 6px;"><strong>Mingguan</strong></p>
                <p style="margin:0 0 6px;">Pendapatan: <strong>Rp {{ number_format($stats['weekly']['revenue'], 0, ',', '.') }}</strong></p>
                <p class="small" style="margin:0;">Order: {{ $stats['weekly']['order_count'] }}</p>
                <p class="small" style="margin:6px 0 0;">{{ $stats['weekly']['start']->format('d-m-Y') }} s/d {{ $stats['weekly']['end']->format('d-m-Y') }}</p>
            </div>

            <div style="padding:14px;border:1px solid #dbe4f7;border-radius:14px;background:#f8fbff;">
                <p class="small" style="margin:0 0 6px;"><strong>Bulanan</strong></p>
                <p style="margin:0 0 6px;">Pendapatan: <strong>Rp {{ number_format($stats['monthly']['revenue'], 0, ',', '.') }}</strong></p>
                <p class="small" style="margin:0;">Order: {{ $stats['monthly']['order_count'] }}</p>
                <p class="small" style="margin:6px 0 0;">{{ $stats['monthly']['start']->format('d-m-Y') }} s/d {{ $stats['monthly']['end']->format('d-m-Y') }}</p>
            </div>
        </div>
    </section>

    <section class="card">
        <h2>Daftar Menu Terlaris</h2>
        <table>
            <thead>
                <tr>
                    <th>Peringkat</th>
                    <th>Menu</th>
                    <th>Total Terjual</th>
                    <th>Omzet Menu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($topMenus as $index => $menu)
                    <tr>
                        <td>#{{ $index + 1 }}</td>
                        <td>{{ $menu->menu_name }}</td>
                        <td>{{ (int) $menu->total_qty }} porsi</td>
                        <td>Rp {{ number_format((float) $menu->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="small">Belum ada transaksi untuk menghitung menu terlaris.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
