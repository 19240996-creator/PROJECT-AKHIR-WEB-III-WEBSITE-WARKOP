@extends('layouts.app')

@section('title', 'Data Menu')

@section('content')
    <div class="card" style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
        <div>
            <h1>Data Menu</h1>
            <p class="small">Kelola menu, harga, status aktif, dan foto menu.</p>
        </div>
        <a href="{{ route('menus.create') }}" class="btn">+ Tambah Menu</a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Stok Terkait</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($menus as $menu)
                    <tr>
                        <td>
                            @if ($menu->image_path)
                                <img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->name }}" style="width:72px;height:72px;object-fit:cover;border-radius:8px;">
                            @else
                                <span class="small">-</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $menu->name }}</strong><br>
                            <span class="small">{{ $menu->description }}</span>
                        </td>
                        <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                        <td>{{ $menu->is_available ? 'Aktif' : 'Nonaktif' }}</td>
                        <td>
                            @if ($menu->stockItem)
                                {{ $menu->stockItem->name }} / {{ $menu->stock_usage }}
                            @else
                                <span class="small">Tidak terhubung</span>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-muted" href="{{ route('menus.edit', $menu) }}">Edit</a>
                            <form action="{{ route('menus.destroy', $menu) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit" onclick="return confirm('Hapus menu ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="small">Belum ada menu.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:12px;">
            {{ $menus->links() }}
        </div>
    </div>
@endsection
