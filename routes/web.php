<?php

use App\Http\Controllers\CashierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [CashierController::class, 'index'])->name('dashboard');
Route::get('/dashboard-insight', [DashboardController::class, 'index'])->name('dashboard.insight');

Route::resource('menus', MenuController::class);
Route::resource('stocks', StockItemController::class);

Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');
Route::post('/cashier', [CashierController::class, 'store'])->name('cashier.store');
Route::post('/cashier/recent/reset', [CashierController::class, 'resetRecentHistory'])->name('cashier.recent.reset');
Route::get('/cashier/{cashier}', [CashierController::class, 'show'])->name('cashier.show');

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

Route::get('/receipts/{order}/customer', [ReceiptController::class, 'customer'])->name('receipts.customer');
Route::get('/receipts/{order}/cashier', [ReceiptController::class, 'cashier'])->name('receipts.cashier');
