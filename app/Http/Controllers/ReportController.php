<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'daily');
        $range = $this->periodRange($period);

        $query = Order::with('items')->latest('ordered_at');
        $this->applyPeriodScope($query, $range['period'], $range['start'], $range['end']);

        $orders = $query->paginate(20)->withQueryString();

        $summaryQuery = Order::query();
        $this->applyPeriodScope($summaryQuery, $range['period'], $range['start'], $range['end']);

        $summary = [
            'order_count' => (clone $summaryQuery)->count(),
            'gross_revenue' => (clone $summaryQuery)->sum('subtotal'),
            'net_revenue' => (clone $summaryQuery)->sum('total'),
        ];

        $period = $range['period'];

        return view('reports.index', compact('period', 'orders', 'summary'));
    }

    public function export(Request $request)
    {
        $period = $request->get('period', 'daily');
        $range = $this->periodRange($period);

        $orders = Order::with('items')->latest('ordered_at');
        $this->applyPeriodScope($orders, $range['period'], $range['start'], $range['end']);

        $rows = $orders->get();
        $fileName = sprintf('laporan-transaksi-%s-%s.csv', $range['period'], Carbon::now()->format('Ymd_His'));

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'wb');

            // UTF-8 BOM so Excel can read Indonesian characters correctly.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Invoice',
                'Waktu',
                'Customer',
                'Kasir',
                'Metode Pembayaran',
                'Subtotal',
                'Total',
                'Item',
            ]);

            foreach ($rows as $order) {
                $itemSummary = $order->items
                    ->map(fn ($item) => $item->menu_name . ' x' . $item->quantity)
                    ->implode('; ');

                fputcsv($handle, [
                    $order->invoice_number,
                    optional($order->ordered_at)->format('Y-m-d H:i:s'),
                    $order->customer_name ?: '-',
                    $order->cashier_name,
                    strtoupper($order->payment_method),
                    (float) $order->subtotal,
                    (float) $order->total,
                    $itemSummary,
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function periodRange(string $period): array
    {
        $now = Carbon::now();

        if ($period === 'weekly') {
            return [
                'period' => 'weekly',
                'start' => $now->copy()->startOfWeek(),
                'end' => $now->copy()->endOfWeek(),
            ];
        }

        if ($period === 'monthly') {
            return [
                'period' => 'monthly',
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth(),
            ];
        }

        return [
            'period' => 'daily',
            'start' => $now->copy()->startOfDay(),
            'end' => $now->copy()->endOfDay(),
        ];
    }

    private function applyPeriodScope($query, string $period, Carbon $start, Carbon $end): void
    {
        if ($period === 'daily') {
            $query->whereDate('ordered_at', $start->toDateString());

            return;
        }

        $query->whereBetween('ordered_at', [$start, $end]);
    }

}
