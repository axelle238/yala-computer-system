<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Products\Index as ProductIndex;
use App\Livewire\Store\Home as StoreHome;
use App\Livewire\Transactions\Create as TransactionCreate;
use Illuminate\Support\Facades\Route;

// Public Storefront
Route::get('/', StoreHome::class)->name('home');
Route::get('/product/{id}', \App\Livewire\Store\ProductDetail::class)->name('product.detail');
Route::get('/berita', \App\Livewire\Store\News\Index::class)->name('news.index');
Route::get('/berita/{slug}', \App\Livewire\Store\News\Show::class)->name('news.show');
Route::get('/rakit-pc', \App\Livewire\Store\PcBuilder::class)->name('pc-builder');
Route::get('/garansi', \App\Livewire\Store\WarrantyCheck::class)->name('warranty-check');
Route::get('/track-service', \App\Livewire\Front\TrackService::class)->name('track-service'); // New Tracking
Route::get('/cart', \App\Livewire\Store\Cart::class)->name('cart');
Route::get('/checkout', \App\Livewire\Store\Checkout::class)->name('checkout');
Route::get('/order-success/{id}', \App\Livewire\Store\OrderSuccess::class)->name('order.success');
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::view('/privacy-policy', 'store.privacy-policy')->name('privacy-policy');
Route::view('/terms-of-service', 'store.terms-of-service')->name('terms-of-service');

// Customer Auth
Route::get('/customer/login', \App\Livewire\Store\Auth\Login::class)->name('customer.login')->middleware('guest');
Route::get('/customer/register', \App\Livewire\Store\Auth\Register::class)->name('customer.register')->middleware('guest');

// Admin/Staff Login
Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::post('/logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Admin Panel (Protected)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/products', ProductIndex::class)->name('products.index');
    Route::get('/products/create', \App\Livewire\Products\Form::class)->name('products.create');
    Route::get('/products/{id}/edit', \App\Livewire\Products\Form::class)->name('products.edit');
    Route::get('/products/labels', \App\Livewire\Products\LabelMaker::class)->name('products.labels'); // New

    Route::get('/transactions', \App\Livewire\Transactions\Index::class)->name('transactions.index');
    Route::get('/transactions/create', TransactionCreate::class)->name('transactions.create');
    Route::get('/settings', \App\Livewire\Settings\Index::class)->name('settings.index');
    
    // Master Data
    Route::get('/master/categories', \App\Livewire\Master\Categories::class)->name('master.categories');
    Route::get('/master/categories/create', \App\Livewire\Master\Categories\Form::class)->name('master.categories.create');
    Route::get('/master/categories/{id}/edit', \App\Livewire\Master\Categories\Form::class)->name('master.categories.edit');
    
    Route::get('/master/suppliers', \App\Livewire\Master\Suppliers::class)->name('master.suppliers');
    Route::get('/master/suppliers/create', \App\Livewire\Master\Suppliers\Form::class)->name('master.suppliers.create');
    Route::get('/master/suppliers/{id}/edit', \App\Livewire\Master\Suppliers\Form::class)->name('master.suppliers.edit');

    // Employee Management (Admin Only)
    Route::get('/employees', \App\Livewire\Employees\Index::class)->name('employees.index');
    Route::get('/employees/roles', \App\Livewire\Employees\Roles::class)->name('employees.roles');
    Route::get('/employees/create', \App\Livewire\Employees\Form::class)->name('employees.create');
    Route::get('/employees/{id}/edit', \App\Livewire\Employees\Form::class)->name('employees.edit');
    Route::get('/payroll', \App\Livewire\Employees\Payroll::class)->name('employees.payroll');
    Route::get('/expenses', \App\Livewire\Expenses\Index::class)->name('expenses.index');
    Route::get('/finance/profit-loss', \App\Livewire\Finance\ProfitLoss::class)->name('finance.profit-loss');
    
    // CRM
    Route::get('/customers', \App\Livewire\Customers\Index::class)->name('customers.index');
    Route::get('/customers/create', \App\Livewire\Customers\Form::class)->name('customers.create');
    Route::get('/customers/{id}/edit', \App\Livewire\Customers\Form::class)->name('customers.edit');

    // Audit Log
    Route::get('/activity-logs', \App\Livewire\ActivityLogs\Index::class)->name('activity-logs.index');
    Route::get('/activity-logs/{id}', \App\Livewire\ActivityLogs\Show::class)->name('activity-logs.show');

    // Banners (Admin Only)
    Route::get('/banners', \App\Livewire\Banners\Index::class)->name('banners.index');
    Route::get('/banners/create', \App\Livewire\Banners\Form::class)->name('banners.create');
    Route::get('/banners/{id}/edit', \App\Livewire\Banners\Form::class)->name('banners.edit');

    // Service Center
    Route::get('/services', \App\Livewire\Services\Index::class)->name('services.index');
    Route::get('/services/create', \App\Livewire\Services\Form::class)->name('services.create');
    Route::get('/services/{id}/edit', \App\Livewire\Services\Form::class)->name('services.edit');

    // Purchase Orders (Procurement)
    Route::get('/purchase-orders', \App\Livewire\PurchaseOrders\Index::class)->name('purchase-orders.index');
    Route::get('/purchase-orders/create', \App\Livewire\PurchaseOrders\Form::class)->name('purchase-orders.create');
    Route::get('/purchase-orders/{id}/edit', \App\Livewire\PurchaseOrders\Form::class)->name('purchase-orders.edit');
    Route::get('/purchase-orders/{po}', \App\Livewire\PurchaseOrders\Show::class)->name('purchase-orders.show');

    // Online Orders (Sales)
    Route::get('/orders', \App\Livewire\Orders\Index::class)->name('orders.index');
    Route::get('/orders/{id}', \App\Livewire\Orders\Show::class)->name('orders.show');

    // Warehouse
    Route::get('/warehouses/transfer', \App\Livewire\Warehouses\Transfer::class)->name('warehouses.transfer');
    Route::get('/warehouses/stock-opname', \App\Livewire\Warehouses\StockOpname::class)->name('warehouses.stock-opname');

    // Marketing
    Route::get('/marketing/flash-sale', \App\Livewire\Marketing\FlashSale\Index::class)->name('marketing.flash-sale.index');
    Route::get('/marketing/flash-sale/create', \App\Livewire\Marketing\FlashSale\Form::class)->name('marketing.flash-sale.create');

    // News Management
    Route::get('/news', \App\Livewire\News\Index::class)->name('admin.news.index');
    Route::get('/news/create', \App\Livewire\News\Form::class)->name('admin.news.create');
    Route::get('/news/{id}/edit', \App\Livewire\News\Form::class)->name('admin.news.edit');

    // Printing Routes
    Route::get('/print/transaction/{id}', [\App\Http\Controllers\PrintController::class, 'transaction'])->name('print.transaction');
    Route::get('/print/service/{id}', [\App\Http\Controllers\PrintController::class, 'service'])->name('print.service');
    Route::get('/print/label/{id}', [\App\Http\Controllers\PrintController::class, 'productLabel'])->name('print.label');
    Route::get('/print/labels', [\App\Http\Controllers\PrintController::class, 'labels'])->name('print.labels'); // New
});
