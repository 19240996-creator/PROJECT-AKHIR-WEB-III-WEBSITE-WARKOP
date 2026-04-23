<div style="margin-bottom:10px;">
    <label>Nama Barang</label>
    <input class="input" type="text" name="name" value="{{ old('name', $stockItem->name ?? '') }}" required>
</div>

<div class="form-grid">
    <div>
        <label>Satuan</label>
        <input class="input" type="text" name="unit" value="{{ old('unit', $stockItem->unit ?? 'pcs') }}" required>
    </div>
    <div>
        <label>Jumlah Saat Ini</label>
        <input class="input" type="number" name="quantity" min="0" step="0.01" value="{{ old('quantity', $stockItem->quantity ?? 0) }}" required>
    </div>
    <div>
        <label>Batas Minimum</label>
        <input class="input" type="number" name="minimum_quantity" min="0" step="0.01" value="{{ old('minimum_quantity', $stockItem->minimum_quantity ?? 0) }}" required>
    </div>
</div>

<div style="margin-top:10px;">
    <label>Catatan</label>
    <textarea class="input" name="note" rows="3">{{ old('note', $stockItem->note ?? '') }}</textarea>
</div>

<div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-muted" href="{{ route('stocks.index') }}">Kembali</a>
</div>
