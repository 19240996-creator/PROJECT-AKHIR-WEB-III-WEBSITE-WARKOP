<div style="margin-bottom:10px;">
    <label>Nama Menu</label>
    <input class="input" type="text" name="name" value="{{ old('name', $menu->name ?? '') }}" required>
</div>

<div style="margin-bottom:10px;">
    <label>Deskripsi</label>
    <textarea class="input" name="description" rows="3">{{ old('description', $menu->description ?? '') }}</textarea>
</div>

<div style="margin-bottom:10px;">
    <label>Harga</label>
    <input class="input" type="number" name="price" min="0" step="0.01" value="{{ old('price', $menu->price ?? '') }}" required>
</div>

<div style="margin-bottom:10px;">
    <label>Foto Menu</label>
    <input class="input" type="file" name="image" accept="image/*">
    @if (!empty($menu?->image_path))
        <div style="margin-top:8px;">
            <img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->name }}" style="max-width:130px;border-radius:8px;">
        </div>
    @endif
</div>

<div style="margin-bottom:10px;">
    <label>Stok Barang Terkait (opsional)</label>
    <select class="input" name="stock_item_id">
        <option value="">- Tidak terhubung -</option>
        @foreach ($stockItems as $stock)
            <option value="{{ $stock->id }}" {{ (string) old('stock_item_id', $menu->stock_item_id ?? '') === (string) $stock->id ? 'selected' : '' }}>
                {{ $stock->name }} ({{ number_format($stock->quantity, 2, ',', '.') }} {{ $stock->unit }})
            </option>
        @endforeach
    </select>
</div>

<div style="margin-bottom:10px;">
    <label>Pemakaian Stok Per Porsi</label>
    <input class="input" type="number" name="stock_usage" min="0.01" step="0.01" value="{{ old('stock_usage', $menu->stock_usage ?? 1) }}">
</div>

<div style="margin-bottom:14px;">
    <label style="display:flex;gap:8px;align-items:center;">
        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $menu->is_available ?? true) ? 'checked' : '' }}>
        Menu tersedia untuk dijual
    </label>
</div>

<div style="display:flex;gap:8px;flex-wrap:wrap;">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-muted" href="{{ route('menus.index') }}">Kembali</a>
</div>
