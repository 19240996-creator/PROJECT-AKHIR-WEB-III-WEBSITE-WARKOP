<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function index()
    {
        $menus = Menu::with('stockItem')
            ->where('is_available', true)
            ->orderBy('name')
            ->get();

        $recentResetAt = session('recent_history_reset_at');

        $recentOrdersQuery = Order::query()->latest('ordered_at');
        if ($recentResetAt) {
            $recentOrdersQuery->where('ordered_at', '>=', $recentResetAt);
        }

        $recentOrders = $recentOrdersQuery->limit(10)->get();

        $topMenus = OrderItem::query()
            ->select(
                'menu_id',
                'menu_name',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(line_total) as total_revenue')
            )
            ->groupBy('menu_id', 'menu_name')
            ->orderByDesc('total_qty')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        $topMenuToday = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->select(
                'order_items.menu_id',
                'order_items.menu_name',
                DB::raw('SUM(order_items.quantity) as total_qty')
            )
            ->whereDate('orders.ordered_at', Carbon::today()->toDateString())
            ->groupBy('order_items.menu_id', 'order_items.menu_name')
            ->orderByDesc('total_qty')
            ->first();

        return view('cashier.index', compact('menus', 'recentOrders', 'recentResetAt', 'topMenus', 'topMenuToday'));
    }

    public function resetRecentHistory(Request $request)
    {
        $request->session()->put('recent_history_reset_at', Carbon::now()->format('Y-m-d H:i:s'));

        return redirect()->route('cashier.index')
            ->with('success', 'Riwayat transaksi terbaru berhasil di-reset. Data laporan tetap aman dan tidak terhapus.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'cashier_name' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'in:cash,qris'],
            'client_ordered_at' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'exists:menus,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $selectedItems = collect($validated['items']);
        $menuIds = $selectedItems->pluck('menu_id')->all();
        $menus = Menu::with('stockItem')->whereIn('id', $menuIds)->get()->keyBy('id');

        $subtotal = 0;
        $orderItemsPayload = [];

        foreach ($selectedItems as $entry) {
            $menu = $menus->get($entry['menu_id']);
            if (! $menu || ! $menu->is_available) {
                return back()->withErrors(['items' => 'Ada menu tidak tersedia.'])->withInput();
            }

            $qty = (int) $entry['quantity'];
            $lineTotal = (float) $menu->price * $qty;
            $subtotal += $lineTotal;

            $orderItemsPayload[] = [
                'menu_id' => $menu->id,
                'menu_name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $qty,
                'line_total' => $lineTotal,
                'menu' => $menu,
            ];

            if ($menu->stockItem) {
                $requiredStock = (float) $menu->stock_usage * $qty;
                if ((float) $menu->stockItem->quantity < $requiredStock) {
                    return back()->withErrors([
                        'items' => "Stok {$menu->stockItem->name} tidak cukup untuk {$menu->name}.",
                    ])->withInput();
                }
            }
        }

        $total = $subtotal;

        $order = DB::transaction(function () use ($validated, $subtotal, $total, $orderItemsPayload) {
            $now = isset($validated['client_ordered_at'])
                ? Carbon::createFromFormat('Y-m-d H:i:s', $validated['client_ordered_at'])
                : Carbon::now();
            $invoiceNumber = sprintf('INV-%s-%s', $now->format('Ymd'), Str::upper(Str::random(6)));

            while (Order::where('invoice_number', $invoiceNumber)->exists()) {
                $invoiceNumber = sprintf('INV-%s-%s', $now->format('Ymd'), Str::upper(Str::random(6)));
            }

            $lastQueue = Order::whereDate('ordered_at', $now->toDateString())->max('queue_number');
            $queueNumber = ((int) $lastQueue) + 1;

            $order = Order::create([
                'invoice_number' => $invoiceNumber,
                'queue_number' => $queueNumber,
                'customer_name' => $validated['customer_name'] ?? null,
                'subtotal' => $subtotal,
                'discount' => 0,
                'tax' => 0,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'cashier_name' => $validated['cashier_name'] ?? 'Kasir',
                'ordered_at' => $now,
            ]);

            foreach ($orderItemsPayload as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'menu_name' => $item['menu_name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);

                $menu = $item['menu'];
                if ($menu->stockItem) {
                    $usedAmount = (float) $menu->stock_usage * $item['quantity'];
                    $menu->stockItem->decrement('quantity', $usedAmount);
                }
            }

            return $order;
        });

        return redirect()->route('cashier.show', $order)->with('success', 'Pesanan berhasil diproses.');
    }

    public function show(Order $cashier)
    {
        $cashier->load('items');

        return view('cashier.show', ['order' => $cashier]);
    }
}
