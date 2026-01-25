<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Products\Index as ProductIndex;
use App\Livewire\Store\Home as StoreHome;
use App\Livewire\Transactions\Create as TransactionCreate;
use Illuminate\Support\Facades\Route;

// Public Storefront
Route::get('/', StoreHome::class)->name('home');
Route::get('/katalog', \App\Livewire\Store\Catalog::class)->name('store.catalog');
Route::get('/merek', \App\Livewire\Store\Brands::class)->name('store.brands');
Route::get('/produk/{id}', \App\Livewire\Store\ProductDetail::class)->name('product.detail');
Route::get('/paket/{slug}', \App\Livewire\Store\BundleDetail::class)->name('store.bundle.detail');
Route::get('/bandingkan', \App\Livewire\Store\Comparison::class)->name('product.compare');
Route::get('/community', \App\Livewire\Community\Gallery::class)->name('community.index');
Route::get('/berita', \App\Livewire\Store\News\Index::class)->name('news.index');
Route::get('/berita/{slug}', \App\Livewire\Store\News\Show::class)->name('news.show');
Route::get('/pusat-bantuan', \App\Livewire\Store\HelpCenter::class)->name('help.center');
Route::get('/rakit-pc', \App\Livewire\Store\PcBuilder::class)->name('pc-builder');
Route::get('/garansi', \App\Livewire\Store\WarrantyCheck::class)->name('warranty-check');
Route::get('/lacak-servis', \App\Livewire\Front\TrackService::class)->name('track-service');
Route::get('/lacak-pesanan', \App\Livewire\Front\TrackOrder::class)->name('track-order');
Route::get('/hubungi-kami', \App\Livewire\Store\ContactUs::class)->name('store.contact');
Route::get('/tentang-kami', \App\Livewire\Store\AboutUs::class)->name('store.about');
Route::get('/keranjang', \App\Livewire\Store\Cart::class)->name('cart');
Route::get('/pembayaran-aman', \App\Livewire\Store\Checkout::class)->name('checkout.secure')->middleware('store.configured');
Route::get('/pembayaran', \App\Livewire\Store\Checkout::class)->name('checkout')->middleware('store.configured');
Route::get('/keinginan', \App\Livewire\Store\Wishlist::class)->name('wishlist');
Route::get('/pesanan-berhasil/{id}', \App\Livewire\Store\OrderSuccess::class)->name('order.success');
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::view('/privacy-policy', 'store.privacy-policy')->name('privacy-policy');
Route::view('/terms-of-service', 'store.terms-of-service')->name('terms-of-service');

// Customer Auth
Route::get('/pelanggan/masuk', \App\Livewire\Store\Auth\Login::class)->name('customer.login')->middleware('guest');
Route::get('/pelanggan/daftar', \App\Livewire\Store\Auth\Register::class)->name('customer.register')->middleware('guest');
Route::get('/pelanggan/lupa-sandi', \App\Livewire\Store\Auth\ForgotPassword::class)->name('customer.forgot-password')->middleware('guest');

// Admin/Staff Login
Route::get('/masuk', Login::class)->name('login')->middleware('guest');
Route::post('/keluar', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect('/');
})->name('logout');

// Member Area (Protected)
Route::prefix('anggota')->middleware('auth')->group(function () {
    Route::get('/beranda', \App\Livewire\Member\Dashboard::class)->name('member.dashboard');
    Route::get('/alamat', \App\Livewire\Member\Addresses::class)->name('member.addresses');
    Route::get('/servis/pesan', \App\Livewire\Service\Booking::class)->name('service.booking');
    Route::get('/pesanan', \App\Livewire\Member\Orders::class)->name('member.orders');
    Route::get('/pesanan/{id}', \App\Livewire\Member\OrderDetail::class)->name('member.orders.show');
    Route::get('/penawaran', \App\Livewire\Member\Quotations::class)->name('member.quotations');
    Route::get('/profil', \App\Livewire\Member\ProfileSettings::class)->name('member.profile');
    Route::get('/referal', \App\Livewire\Member\Referrals::class)->name('member.referrals');
    Route::get('/loyalitas', \App\Livewire\Member\LoyaltyPoints::class)->name('member.loyalty');
    Route::get('/garansi/ajukan', \App\Livewire\Member\RmaRequest::class)->name('member.rma.request');
});

// Admin Panel (Protected)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    // Tugas & Kolaborasi
    Route::get('/tugas', \App\Livewire\Admin\TaskManager::class)->name('admin.tasks');

    // Garansi (Admin)
    Route::get('/garansi', \App\Livewire\Rma\Manager::class)->name('rma.index');
    Route::get('/garansi/buat', \App\Livewire\Rma\Form::class)->name('rma.create');
    Route::get('/garansi/{id}/ubah', \App\Livewire\Rma\Form::class)->name('rma.edit');

    // Produk
    Route::get('/produk', ProductIndex::class)->name('products.index');
    Route::get('/produk/paket', \App\Livewire\Products\Bundles::class)->name('products.bundles');
    Route::get('/produk/buat', \App\Livewire\Products\Form::class)->name('products.create');
    Route::get('/produk/{id}/ubah', \App\Livewire\Products\Form::class)->name('products.edit');
    Route::get('/produk/label', \App\Livewire\Products\LabelMaker::class)->name('products.labels');

    // Transaksi
    Route::get('/transaksi', \App\Livewire\Transactions\Index::class)->name('transactions.index');
    Route::get('/transaksi/buat', TransactionCreate::class)->name('transactions.create');
    
    // Pengaturan
    Route::get('/pengaturan', \App\Livewire\Settings\Index::class)->name('settings.index');

    // Data Master
    Route::get('/master/kategori', \App\Livewire\Master\Categories::class)->name('master.categories');
    Route::get('/master/kategori/buat', \App\Livewire\Master\Categories\Form::class)->name('master.categories.create');
    Route::get('/master/kategori/{id}/ubah', \App\Livewire\Master\Categories\Form::class)->name('master.categories.edit');
    Route::get('/master/pemasok', \App\Livewire\Master\Suppliers::class)->name('master.suppliers');

    Route::get('/spanduk', \App\Livewire\Banners\Index::class)->name('banners.index');
    Route::get('/spanduk/buat', \App\Livewire\Banners\Form::class)->name('banners.create');
    Route::get('/spanduk/{id}/ubah', \App\Livewire\Banners\Form::class)->name('banners.edit');

    // Manajemen Karyawan
    Route::get('/karyawan/kehadiran', \App\Livewire\Employees\Attendance::class)->name('employees.attendance');
    Route::get('/karyawan/pengelola-gaji', \App\Livewire\Employees\PayrollManager::class)->name('employees.payroll-manager');
    Route::get('/karyawan', \App\Livewire\Employees\Index::class)->name('employees.index');
    Route::get('/karyawan/peran', \App\Livewire\Admin\RoleManager::class)->name('employees.roles');
    Route::get('/karyawan/peran/buat', \App\Livewire\Admin\RoleForm::class)->name('employees.roles.create');
    Route::get('/karyawan/peran/{id}/ubah', \App\Livewire\Admin\RoleForm::class)->name('employees.roles.edit');
    Route::get('/karyawan/buat', \App\Livewire\Employees\Form::class)->name('employees.create');
    Route::get('/karyawan/{id}/ubah', \App\Livewire\Employees\Form::class)->name('employees.edit');
    Route::get('/gaji', \App\Livewire\Employees\Payroll::class)->name('employees.payroll');
    Route::get('/pengeluaran', \App\Livewire\Expenses\Index::class)->name('expenses.index');

    // CRM
    Route::get('/pelanggan', \App\Livewire\Customers\Index::class)->name('customers.index');
    Route::get('/pelanggan/kotak-masuk', \App\Livewire\Admin\Inbox::class)->name('customers.inbox');
    Route::get('/pelanggan/loyalitas', \App\Livewire\CRM\LoyaltyManager::class)->name('customers.loyalty');
    Route::get('/pelanggan/obrolan-langsung', \App\Livewire\Admin\LiveChatManager::class)->name('customers.live-chat');
    Route::get('/pelanggan/buat', \App\Livewire\Customers\Form::class)->name('customers.create');
    Route::get('/pelanggan/{id}', \App\Livewire\CRM\CustomerDetail::class)->name('customers.show');
    Route::get('/pelanggan/{id}/ubah', \App\Livewire\Customers\Form::class)->name('customers.edit');

    // Log Audit
    Route::get('/log-aktivitas', \App\Livewire\ActivityLogs\Index::class)->name('activity-logs.index');
    Route::get('/log-aktivitas/{id}', \App\Livewire\ActivityLogs\Show::class)->name('activity-logs.show');

    // Pusat Servis
    Route::get('/servis/papan', \App\Livewire\Services\Kanban::class)->name('services.kanban');
    Route::get('/servis', \App\Livewire\Services\Index::class)->name('services.index');
    Route::get('/servis/buat', \App\Livewire\Services\Form::class)->name('services.create');
    Route::get('/servis/{id}/ubah', \App\Livewire\Services\Form::class)->name('services.edit');
    Route::get('/servis/{id}/meja-kerja', \App\Livewire\Services\Workbench::class)->name('services.workbench');

    // Perakitan PC
    Route::get('/perakitan', \App\Livewire\Assembly\Manager::class)->name('assembly.manager');

    // Basis Pengetahuan
    Route::get('/pengetahuan', \App\Livewire\Knowledge\Index::class)->name('knowledge.index');

    // Permintaan Pembelian
    Route::get('/permintaan-pembelian', \App\Livewire\PurchaseRequisitions\Index::class)->name('purchase-requisitions.index');
    Route::get('/permintaan-pembelian/buat', \App\Livewire\PurchaseRequisitions\Create::class)->name('purchase-requisitions.create');
    Route::get('/permintaan-pembelian/{id}', \App\Livewire\PurchaseRequisitions\Show::class)->name('purchase-requisitions.show');

    // Pesanan Pembelian (Pengadaan)
    Route::get('/pesanan-pembelian', \App\Livewire\PurchaseOrders\Index::class)->name('purchase-orders.index');
    Route::get('/pesanan-pembelian/terima', \App\Livewire\Procurement\GoodsReceive\Create::class)->name('purchase-orders.receive');
    Route::get('/pesanan-pembelian/buat', \App\Livewire\PurchaseOrders\Form::class)->name('purchase-orders.create');
    Route::get('/pesanan-pembelian/{id}/ubah', \App\Livewire\PurchaseOrders\Form::class)->name('purchase-orders.edit');
    Route::get('/pesanan-pembelian/{po}', \App\Livewire\PurchaseOrders\Show::class)->name('purchase-orders.show');

    // Pesanan Daring (Penjualan)
    Route::get('/pesanan', \App\Livewire\Orders\Index::class)->name('orders.index');
    Route::get('/kasir', \App\Livewire\Sales\PointOfSale::class)->name('sales.pos')->middleware('store.configured');
    Route::get('/pesanan/logistik', \App\Livewire\Logistics\Manager::class)->name('logistics.manager');
    Route::get('/pesanan/{id}', \App\Livewire\Orders\Show::class)->name('orders.show');

    // Gudang
    Route::get('/gudang/transfer', \App\Livewire\Warehouses\Transfer::class)->name('warehouses.transfer');
    Route::get('/gudang/stok-opname', \App\Livewire\Warehouses\StockOpname::class)->name('warehouses.stock-opname');

    // Pemasaran
    Route::get('/pemasaran/obral-kilat', \App\Livewire\Marketing\FlashSale\Index::class)->name('marketing.flash-sale.index');
    Route::get('/pemasaran/obral-kilat/buat', \App\Livewire\Marketing\FlashSale\Form::class)->name('marketing.flash-sale.create');
    Route::get('/pemasaran/ulasan', \App\Livewire\Marketing\Reviews\Manager::class)->name('reviews.manager');
    Route::get('/pemasaran/voucher', \App\Livewire\Marketing\Vouchers\Index::class)->name('marketing.vouchers.index');
    Route::get('/pemasaran/voucher/buat', \App\Livewire\Marketing\Vouchers\Form::class)->name('marketing.vouchers.create');

    // Manajemen Berita
    Route::get('/berita', \App\Livewire\News\Index::class)->name('admin.news.index');
    Route::get('/berita/buat', \App\Livewire\News\Form::class)->name('admin.news.create');
    Route::get('/berita/{id}/ubah', \App\Livewire\News\Form::class)->name('admin.news.edit');

    // Cetak
    Route::get('/cetak/transaksi/{id}', [\App\Http\Controllers\PrintController::class, 'transaction'])->name('print.transaction');
    Route::get('/cetak/servis/{id}', [\App\Http\Controllers\PrintController::class, 'service'])->name('print.service');
    Route::get('/cetak/label/{id}', [\App\Http\Controllers\PrintController::class, 'productLabel'])->name('print.label');
    Route::get('/cetak/label-masal', [\App\Http\Controllers\PrintController::class, 'labels'])->name('print.labels');

    // Manajemen Aset
    Route::get('/aset', \App\Livewire\Assets\Index::class)->name('assets.index');
    Route::get('/aset/buat', \App\Livewire\Assets\Form::class)->name('assets.create');

    // Analitik & HR
    Route::get('/analitik', \App\Livewire\Reports\Index::class)->name('reports.index');
    Route::get('/analitik/keuangan', \App\Livewire\Reports\FinanceReport::class)->name('reports.finance');
    Route::get('/analitik/stok', \App\Livewire\Reports\StockReport::class)->name('reports.stock');
    Route::get('/analitik/penjualan', \App\Livewire\Reports\SalesReport::class)->name('reports.sales');

    Route::get('/shift', \App\Livewire\Shift\Manager::class)->name('shift.manager');
    Route::get('/reimbursement', \App\Livewire\Employees\Reimbursement::class)->name('employees.reimbursement');

    // B2B & Keuangan
    Route::get('/penawaran', \App\Livewire\Quotations\Index::class)->name('quotations.index');
    Route::get('/penawaran/buat', \App\Livewire\Quotations\Form::class)->name('quotations.create');
    Route::get('/penawaran/{id}/ubah', \App\Livewire\Quotations\Form::class)->name('quotations.edit');
    Route::get('/keuangan/kasir', \App\Livewire\Finance\CashRegisterManager::class)->name('finance.cash-register');
    Route::get('/keuangan/piutang', \App\Livewire\Finance\Receivables::class)->name('finance.receivables');
    Route::get('/keuangan/laba-rugi', \App\Livewire\Finance\ProfitLoss::class)->name('finance.profit-loss');

    // Pemeliharaan Sistem
    Route::get('/sistem/kesehatan', \App\Livewire\System\Health::class)->name('system.health');
    Route::get('/sistem/info', \App\Livewire\System\Info::class)->name('system.info');
    Route::get('/sistem/cadangan', \App\Livewire\System\Backups::class)->name('system.backups');
});