<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Products\Index as ProductIndex;
use App\Livewire\Store\Home as StoreHome;
use App\Livewire\Transactions\Create as TransactionCreate;
use Illuminate\Support\Facades\Route;

// Public Storefront
Route::get('/', StoreHome::class)->name('home');

// Authentication
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
});
