<?php

namespace App\Http\Controllers;

use App\Models\Order;

class ReceiptController extends Controller
{
    public function customer(Order $order)
    {
        $order->load('items');

        return view('receipts.customer', compact('order'));
    }

    public function cashier(Order $order)
    {
        $order->load('items.menu.stockItem');

        return view('receipts.cashier', compact('order'));
    }
}
