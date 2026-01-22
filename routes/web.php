<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Products\Index as ProductIndex;
use App\Livewire\Store\Home as StoreHome;
use App\Livewire\Transactions\Create as TransactionCreate;
use Illuminate\Support\Facades\Route;

// Public Storefront
Route::get('/', StoreHome::class)->name('home');
Route::get('/rakit-pc', \App\Livewire\Store\PcBuilder::class)->name('pc-builder');
Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::post('/logout', function () {
    auth()->logout();
    session()->invalidate();
    return redirect('/');
})->name('logout');

// Admin Panel (Protected)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/products', ProductIndex::class)->name('products.index');
    Route::get('/products/create', \App\Livewire\Products\Form::class)->name('products.create');
    Route::get('/products/{id}/edit', \App\Livewire\Products\Form::class)->name('products.edit');
    Route::get('/transactions', \App\Livewire\Transactions\Index::class)->name('transactions.index');
    Route::get('/transactions/create', TransactionCreate::class)->name('transactions.create');
    Route::get('/settings', \App\Livewire\Settings\Index::class)->name('settings.index');
    
    // Employee Management (Admin Only)
    Route::get('/employees', \App\Livewire\Employees\Index::class)->name('employees.index');
    Route::get('/employees/create', \App\Livewire\Employees\Form::class)->name('employees.create');
    Route::get('/employees/{id}/edit', \App\Livewire\Employees\Form::class)->name('employees.edit');
    Route::get('/payroll', \App\Livewire\Employees\Payroll::class)->name('employees.payroll');
    Route::get('/expenses', \App\Livewire\Expenses\Index::class)->name('expenses.index');
    Route::get('/finance/profit-loss', \App\Livewire\Finance\ProfitLoss::class)->name('finance.profit-loss');
    
    // CRM
    Route::get('/customers', \App\Livewire\Customers\Index::class)->name('customers.index');

    // Audit Log
    Route::get('/activity-logs', \App\Livewire\ActivityLogs\Index::class)->name('activity-logs.index');

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

    // Warehouse
    Route::get('/warehouses/transfer', \App\Livewire\Warehouses\Transfer::class)->name('warehouses.transfer');

    // Printing Routes
    Route::get('/print/transaction/{id}', [\App\Http\Controllers\PrintController::class, 'transaction'])->name('print.transaction');
    Route::get('/print/service/{id}', [\App\Http\Controllers\PrintController::class, 'service'])->name('print.service');
    Route::get('/print/label/{id}', [\App\Http\Controllers\PrintController::class, 'productLabel'])->name('print.label');
});
