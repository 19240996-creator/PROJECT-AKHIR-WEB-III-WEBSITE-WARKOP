<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'daily' => $this->revenueByPeriod('daily'),
            'weekly' => $this->revenueByPeriod('weekly'),
            'monthly' => $this->revenueByPeriod('monthly'),
        ];

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
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('stats', 'topMenus'));
    }

    private function revenueByPeriod(string $period): array
    {
        $now = Carbon::now();
        $query = Order::query();

        if ($period === 'weekly') {
            $start = $now->copy()->startOfWeek();
            $end = $now->copy()->endOfWeek();
            $query->whereBetween('ordered_at', [$start, $end]);
        } elseif ($period === 'monthly') {
            $start = $now->copy()->startOfMonth();
            $end = $now->copy()->endOfMonth();
            $query->whereBetween('ordered_at', [$start, $end]);
        } else {
            $start = $now->copy()->startOfDay();
            $end = $now->copy()->endOfDay();
            $query->whereDate('ordered_at', $start->toDateString());
            $period = 'daily';
        }

        return [
            'period' => $period,
            'start' => $start,
            'end' => $end,
            'order_count' => (clone $query)->count(),
            'revenue' => (clone $query)->sum('total'),
        ];
    }
}
