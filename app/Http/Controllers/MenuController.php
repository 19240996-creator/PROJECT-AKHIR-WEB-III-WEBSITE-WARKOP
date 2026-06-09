<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('stockItem')->latest()->paginate(12);

        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        $stockItems = StockItem::orderBy('name')->get();

        return view('menus.create', compact('stockItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],
            'stock_item_id' => ['nullable', 'exists:stock_items,id'],
            'stock_usage' => ['nullable', 'numeric', 'min:0.01'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('menus', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available', true);
        $validated['stock_usage'] = $validated['stock_usage'] ?? 1;

        Menu::create($validated);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function show(Menu $menu)
    {
        return redirect()->route('menus.edit', $menu);
    }

    public function edit(Menu $menu)
    {
        $stockItems = StockItem::orderBy('name')->get();

        return view('menus.edit', compact('menu', 'stockItems'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],
            'stock_item_id' => ['nullable', 'exists:stock_items,id'],
            'stock_usage' => ['nullable', 'numeric', 'min:0.01'],
        ]);

        if ($request->hasFile('image')) {
            if ($menu->image_path && Storage::disk('public')->exists($menu->image_path)) {
                Storage::disk('public')->delete($menu->image_path);
            }

            $validated['image_path'] = $request->file('image')->store('menus', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available', false);
        $validated['stock_usage'] = $validated['stock_usage'] ?? 1;

        $menu->update($validated);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->image_path && Storage::disk('public')->exists($menu->image_path)) {
            Storage::disk('public')->delete($menu->image_path);
        }

        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}
