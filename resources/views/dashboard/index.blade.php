@extends('layouts.app')

@section('title', 'Dashboard Insight')

@section('content')
    <section class="card dashboard-hero">
        <div>
            <div class="hero-eyebrow">Dashboard Insight</div>
            <h1>Ringkasan kinerja Warkop hari ini</h1>
            <p class="small">Pantau pendapatan, jumlah order, dan menu terlaris untuk ambil keputusan cepat.</p>
            <div class="hero-actions">
                <a class="btn" href="{{ route('dashboard') }}">Buka Kasir</a>
                <a class="btn btn-muted" href="{{ route('menus.index') }}">Kelola Menu</a>
                <a class="btn btn-muted" href="{{ route('reports.index') }}">Lihat Laporan</a>
            </div>
        </div>
        <div class="hero-panel">
            <h3>Target Hari Ini</h3>
            <p class="small">Pendapatan harian dan total order terbaru.</p>
            <p class="stat-value" style="color:#ffffff;">Rp {{ number_format($stats['daily']['revenue'], 0, ',', '.') }}</p>
            <span class="chip">{{ $stats['daily']['order_count'] }} order</span>
            <p class="small" style="margin-top:10px;">{{ $stats['daily']['start']->format('d-m-Y') }}</p>
        </div>
    </section>

    <section class="card">
        <h2>Statistik Pendapatan</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Harian</div>
                <div class="stat-value">Rp {{ number_format($stats['daily']['revenue'], 0, ',', '.') }}</div>
                <p class="small" style="margin:0;">{{ $stats['daily']['order_count'] }} order</p>
                <p class="small" style="margin:6px 0 0;">{{ $stats['daily']['start']->format('d-m-Y') }}</p>
            </div>
            <div class="stat-card">
                <div class="stat-label">Mingguan</div>
                <div class="stat-value">Rp {{ number_format($stats['weekly']['revenue'], 0, ',', '.') }}</div>
                <p class="small" style="margin:0;">{{ $stats['weekly']['order_count'] }} order</p>
                <p class="small" style="margin:6px 0 0;">{{ $stats['weekly']['start']->format('d-m-Y') }} s/d {{ $stats['weekly']['end']->format('d-m-Y') }}</p>
            </div>
            <div class="stat-card">
                <div class="stat-label">Bulanan</div>
                <div class="stat-value">Rp {{ number_format($stats['monthly']['revenue'], 0, ',', '.') }}</div>
                <p class="small" style="margin:0;">{{ $stats['monthly']['order_count'] }} order</p>
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
                        <td><span class="rank">{{ $index + 1 }}</span></td>
                        <td>
                            <strong>{{ $menu->menu_name }}</strong>
                            <div class="small">Menu favorit pelanggan</div>
                        </td>
                        <td><span class="chip">{{ (int) $menu->total_qty }} porsi</span></td>
                        <td><strong>Rp {{ number_format((float) $menu->total_revenue, 0, ',', '.') }}</strong></td>
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
