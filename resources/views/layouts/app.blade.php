<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Warkop Capitalist')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0b2e63;
            --bg-2: #103f88;
            --card: rgba(255, 255, 255, 0.94);
            --ink: #2a1d14;
            --muted: #6d5344;
            --accent: #0e8a72;
            --accent-dark: #0a6b58;
            --accent-soft: #def4ee;
            --danger: #a62f28;
            --border: #d6dff2;
            --shadow: 0 16px 34px rgba(63, 34, 20, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--ink);
            background:
                radial-gradient(900px 500px at -5% -20%, rgba(255, 255, 255, 0.2) 0, transparent 52%),
                radial-gradient(700px 420px at 105% -10%, rgba(210, 45, 58, 0.36) 0, transparent 56%),
                linear-gradient(145deg, var(--bg) 0%, var(--bg-2) 46%, #07234e 100%);
            font-family: "Plus Jakarta Sans", "Segoe UI", sans-serif;
            position: relative;
            min-height: 100vh;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background-image:
                repeating-linear-gradient(25deg, rgba(255, 255, 255, 0.08) 0 10px, transparent 10px 22px),
                repeating-linear-gradient(-25deg, rgba(209, 35, 49, 0.14) 0 12px, transparent 12px 25px);
            opacity: 0.55;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px 20px 36px;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            background: linear-gradient(90deg, rgba(9, 39, 85, 0.78) 0%, rgba(186, 28, 49, 0.66) 55%, rgba(8, 33, 74, 0.78) 100%);
        }

        .topbar-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            gap: 12px;
            flex-wrap: wrap;
        }

        .brand {
            display: flex;
            flex-direction: column;
            line-height: 1;
        }

        .brand strong {
            font-family: "Bebas Neue", sans-serif;
            font-size: 34px;
            letter-spacing: 1.2px;
            font-weight: 400;
            color: #ffffff;
        }

        .brand span {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.86);
            letter-spacing: 0.8px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .nav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .nav a {
            text-decoration: none;
            color: #ffffff;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.28);
            font-weight: 700;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.12);
            transition: transform 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
        }

        .nav a:hover {
            border-color: rgba(255, 255, 255, 0.48);
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .nav a.active {
            background: linear-gradient(135deg, #ffffff 0%, #e8eefb 100%);
            color: #133265;
            border-color: transparent;
        }

        .card {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.97) 0%, rgba(244, 247, 255, 0.95) 100%);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 16px 34px rgba(9, 27, 60, 0.26);
            padding: 18px;
            margin-bottom: 18px;
            backdrop-filter: blur(5px);
        }

        .dashboard-hero {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.8fr);
            gap: 18px;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .dashboard-hero::after {
            content: "";
            position: absolute;
            inset: -40px -60px auto auto;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(14, 138, 114, 0.45) 0%, transparent 65%);
            opacity: 0.8;
        }

        .hero-eyebrow {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #1c3f7b;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px;
        }

        .hero-panel {
            background: linear-gradient(140deg, rgba(17, 67, 150, 0.92) 0%, rgba(12, 45, 106, 0.95) 100%);
            color: #ffffff;
            border-radius: 18px;
            padding: 16px;
            box-shadow: 0 14px 30px rgba(6, 26, 60, 0.35);
        }

        .hero-panel h3 {
            color: #ffffff;
            margin-bottom: 8px;
        }

        .hero-panel .small {
            color: rgba(255, 255, 255, 0.8);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
            margin-top: 12px;
        }

        .stat-card {
            padding: 16px;
            border: 1px solid #dbe4f7;
            border-radius: 16px;
            background: #f8fbff;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: "";
            position: absolute;
            right: -30px;
            top: -30px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(14, 138, 114, 0.18) 0%, transparent 70%);
        }

        .stat-label {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #335a95;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 800;
            color: #0d2d5e;
            margin-bottom: 6px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            background: #e7efff;
            color: #1e3f78;
        }

        .rank {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 10px;
            background: #103f88;
            color: #ffffff;
            font-weight: 800;
            font-size: 12px;
        }

        .cashier-hero {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.8fr);
            gap: 18px;
            align-items: center;
            position: relative;
        }

        .cashier-hero .chip {
            background: #def4ee;
            color: #0a6b58;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 10px;
            margin-top: 12px;
        }

        .quick-stat {
            background: #f5f9ff;
            border: 1px solid #dbe4f7;
            border-radius: 14px;
            padding: 10px 12px;
        }

        .quick-stat strong {
            display: block;
            font-size: 16px;
            color: #0d2d5e;
            margin-top: 4px;
        }

        .panel-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }

        .item-row {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr) auto;
            gap: 8px;
            padding: 10px;
            border: 1px dashed #cad7f6;
            border-radius: 14px;
            background: #f9fbff;
            align-items: end;
        }

        .item-row + .item-row {
            margin-top: 8px;
        }

        .section-divider {
            height: 1px;
            background: #dbe4f7;
            margin: 14px 0;
        }

        .stock-hero {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, 0.9fr);
            gap: 16px;
            align-items: center;
        }

        .stock-hero .chip {
            background: #fff1d6;
            color: #7a4b12;
        }

        .stock-kpis {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 10px;
            margin-top: 12px;
        }

        .stock-kpi {
            background: #f6f9ff;
            border: 1px solid #dbe4f7;
            border-radius: 14px;
            padding: 10px 12px;
        }

        .stock-kpi strong {
            display: block;
            font-size: 16px;
            color: #0d2d5e;
            margin-top: 4px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 800;
        }

        .status-low {
            background: #ffd7cf;
            color: #7b1f16;
        }

        .status-ok {
            background: #daf2e3;
            color: #1c5e3f;
        }

        .form-card {
            max-width: 720px;
            margin: 0 auto;
        }

        .report-hero {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.8fr);
            gap: 16px;
            align-items: center;
        }

        .report-hero .chip {
            background: #e8f1ff;
            color: #1a3d7a;
        }

        .report-filters {
            display: flex;
            gap: 10px;
            align-items: end;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .report-filters .btn {
            min-width: 140px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
        }

        .summary-card {
            padding: 16px;
            border: 1px solid #dbe4f7;
            border-radius: 16px;
            background: #f7fbff;
        }

        .summary-card strong {
            display: block;
            font-size: 18px;
            margin-top: 6px;
            color: #0d2d5e;
        }

        .payment-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: 800;
            background: #e7efff;
            color: #1e3f78;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #d2dbf1;
            border-radius: 14px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.88);
        }

        tbody tr {
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        tbody tr:hover {
            background: rgba(231, 239, 255, 0.7);
            transform: translateY(-1px);
        }

        th {
            background: #edf2ff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 12px;
            font-weight: 800;
            color: #294777;
        }

        .small {
            color: #4f5d7d;
            font-size: 13px;
            line-height: 1.55;
        }

        .btn-muted {
            background: #e9efff;
            color: #25416e;
            box-shadow: none;
            border: 1px solid #cad7f6;
        }

        .input, select, textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #c8d7fa;
            border-radius: 11px;
            background: #ffffff;
            font: inherit;
        }

        .input:focus,
        select:focus,
        textarea:focus {
            outline: 2px solid rgba(20, 75, 173, 0.16);
            border-color: #2f62c8;
        }

        .success {
            background: #e9f7ff;
            border: 1px solid #b8ddf4;
            color: #114770;
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 14px;
        }

        .errors {
            background: #fff0f2;
            border: 1px solid #f0bcc4;
            color: #8a2431;
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 14px;
        }

        .badge {
            display: inline-block;
            border-radius: 999px;
            padding: 5px 11px;
            font-size: 12px;
            font-weight: 700;
            background: #e9f0ff;
            color: #1c3f7b;
        }

        h1,
        h2,
        h3 {
            margin: 0 0 10px;
            letter-spacing: -0.02em;
        }

        h1 {
            font-size: 30px;
            font-weight: 800;
        }

        h2 {
            font-size: 22px;
            font-weight: 800;
        }

        h3 {
            font-size: 18px;
            font-weight: 800;
        }

        .grid {
            display: grid;
            gap: 18px;
        }

        .grid-2 {
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }

        th, td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #dbe4f7;
            vertical-align: top;
        }

        tr:last-child td {
            border-bottom: 0;
        }

        .btn {
            background: linear-gradient(135deg, var(--accent) 0%, #10a489 100%);
            color: #fff;
            border: 0;
            border-radius: 12px;
            padding: 10px 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 700;
            box-shadow: 0 8px 18px rgba(12, 120, 99, 0.25);
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        .btn:hover {
            filter: brightness(0.96);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
            box-shadow: none;
        }

        @media (max-width: 640px) {
            .container { padding: 12px; }
            .brand strong { font-size: 28px; }
            th, td { padding: 8px 6px; font-size: 14px; }
            .btn { width: 100%; text-align: center; margin-bottom: 8px; }
            .dashboard-hero { grid-template-columns: 1fr; }
            .cashier-hero { grid-template-columns: 1fr; }
            .item-row { grid-template-columns: 1fr; }
            .stock-hero { grid-template-columns: 1fr; }
            .report-hero { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <strong>Warkop Capitalist</strong>
                <span>Point of Sale</span>
            </div>
            <nav class="nav">
                <a href="{{ route('dashboard.insight') }}" class="{{ request()->routeIs('dashboard.insight') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') || request()->routeIs('cashier.*') ? 'active' : '' }}">Kasir</a>
                <a href="{{ route('menus.index') }}" class="{{ request()->routeIs('menus.*') ? 'active' : '' }}">Menu</a>
                <a href="{{ route('stocks.index') }}" class="{{ request()->routeIs('stocks.*') ? 'active' : '' }}">Stok</a>
                <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">Laporan</a>
            </nav>
        </div>
    </header>

    <main class="container">
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="errors">
                <strong>Ada input yang belum valid:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
