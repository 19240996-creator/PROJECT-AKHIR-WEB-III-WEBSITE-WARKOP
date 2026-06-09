<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;

class StockItemController extends Controller
{
    public function index()
    {
        $stockItems = StockItem::latest()->paginate(15);
        $lowStockCount = StockItem::whereColumn('quantity', '<=', 'minimum_quantity')->count();

        return view('stocks.index', compact('stockItems', 'lowStockCount'));
    }

    public function create()
    {
        return view('stocks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:50'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'minimum_quantity' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
        ]);

        StockItem::create($validated);

        return redirect()->route('stocks.index')->with('success', 'Stok barang berhasil ditambahkan.');
    }

    public function show(StockItem $stockItem)
    {
        return redirect()->route('stocks.edit', $stockItem);
    }

    public function edit(StockItem $stockItem)
    {
        return view('stocks.edit', compact('stockItem'));
    }

    public function update(Request $request, StockItem $stockItem)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:50'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'minimum_quantity' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
        ]);

        $stockItem->update($validated);

        return redirect()->route('stocks.index')->with('success', 'Stok barang berhasil diperbarui.');
    }

    public function destroy(StockItem $stockItem)
    {
        $stockItem->delete();

        return redirect()->route('stocks.index')->with('success', 'Stok barang berhasil dihapus.');
    }
}
