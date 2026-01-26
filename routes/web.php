<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Products\Index as ProductIndex;
use App\Livewire\Store\Home as StoreHome;
use App\Livewire\Transactions\Create as TransactionCreate;
use Illuminate\Support\Facades\Route;

// Storefront Publik
Route::get('/', StoreHome::class)->name('beranda');
Route::get('/katalog', \App\Livewire\Store\Catalog::class)->name('toko.katalog');
Route::get('/merek', \App\Livewire\Store\Brands::class)->name('toko.merek');
Route::get('/produk/{id}', \App\Livewire\Store\ProductDetail::class)->name('toko.produk.detail');
Route::get('/paket/{slug}', \App\Livewire\Store\BundleDetail::class)->name('toko.paket.detail');
Route::get('/bandingkan', \App\Livewire\Store\Comparison::class)->name('toko.bandingkan');
Route::get('/komunitas', \App\Livewire\Community\Gallery::class)->name('toko.komunitas');
Route::get('/berita', \App\Livewire\Store\News\Index::class)->name('toko.berita.indeks');
Route::get('/berita/{slug}', \App\Livewire\Store\News\Show::class)->name('toko.berita.tampil');
Route::get('/pusat-bantuan', \App\Livewire\Store\HelpCenter::class)->name('toko.bantuan');
Route::get('/rakit-pc', \App\Livewire\Store\PcBuilder::class)->name('toko.rakit-pc');
Route::get('/garansi', \App\Livewire\Store\WarrantyCheck::class)->name('toko.cek-garansi');
Route::get('/lacak-servis', \App\Livewire\Front\TrackService::class)->name('toko.lacak-servis');
Route::get('/lacak-pesanan', \App\Livewire\Front\TrackOrder::class)->name('toko.lacak-pesanan');
Route::get('/hubungi-kami', \App\Livewire\Store\ContactUs::class)->name('toko.kontak');
Route::get('/tentang-kami', \App\Livewire\Store\AboutUs::class)->name('toko.tentang');
Route::get('/keranjang', \App\Livewire\Store\Cart::class)->name('toko.keranjang');
Route::get('/pembayaran-aman', \App\Livewire\Store\Checkout::class)->name('toko.pembayaran.aman')->middleware('store.configured');
Route::get('/pembayaran', \App\Livewire\Store\Checkout::class)->name('toko.pembayaran')->middleware('store.configured');
Route::get('/keinginan', \App\Livewire\Store\Wishlist::class)->name('toko.keinginan');
Route::get('/pesanan-berhasil/{id}', \App\Livewire\Store\OrderSuccess::class)->name('toko.pesanan.berhasil');
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::view('/kebijakan-privasi', 'store.privacy-policy')->name('toko.privasi');
Route::view('/syarat-ketentuan', 'store.terms-of-service')->name('toko.syarat');

// Autentikasi Pelanggan
Route::get('/pelanggan/masuk', \App\Livewire\Store\Auth\Login::class)->name('pelanggan.masuk')->middleware('guest');
Route::get('/pelanggan/daftar', \App\Livewire\Store\Auth\Register::class)->name('pelanggan.daftar')->middleware('guest');
Route::get('/pelanggan/lupa-sandi', \App\Livewire\Store\Auth\ForgotPassword::class)->name('pelanggan.lupa-sandi')->middleware('guest');

// Login Admin/Staf
Route::get('/masuk', Login::class)->name('masuk')->middleware('guest');
Route::post('/keluar', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect('/');
})->name('keluar');

// Area Anggota (Dilindungi)
Route::prefix('anggota')->middleware('auth')->group(function () {
    Route::get('/beranda', \App\Livewire\Member\Dashboard::class)->name('anggota.beranda');
    Route::get('/alamat', \App\Livewire\Member\Addresses::class)->name('anggota.alamat');
    Route::get('/servis/pesan', \App\Livewire\Service\Booking::class)->name('anggota.servis.pesan');
    Route::get('/pesanan', \App\Livewire\Member\Orders::class)->name('anggota.pesanan');
    Route::get('/pesanan/{id}', \App\Livewire\Member\OrderDetail::class)->name('anggota.pesanan.detail');
    Route::get('/penawaran', \App\Livewire\Member\Quotations::class)->name('anggota.penawaran');
    Route::get('/profil', \App\Livewire\Member\ProfileSettings::class)->name('anggota.profil');
    Route::get('/referal', \App\Livewire\Member\Referrals::class)->name('anggota.referal');
    Route::get('/loyalitas', \App\Livewire\Member\LoyaltyPoints::class)->name('anggota.loyalitas');
    Route::get('/garansi/ajukan', \App\Livewire\Member\RmaRequest::class)->name('anggota.garansi.ajukan');
});

// Panel Admin (Dilindungi)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('admin.beranda');

    // Tugas & Kolaborasi
    Route::get('/tugas', \App\Livewire\Admin\PengelolaTugas::class)->name('admin.tugas');

    // Garansi (Admin)
    Route::get('/garansi', \App\Livewire\Rma\Manager::class)->name('admin.garansi.indeks');
    Route::get('/garansi/buat', \App\Livewire\Rma\Form::class)->name('admin.garansi.buat');
    Route::get('/garansi/{id}/ubah', \App\Livewire\Rma\Form::class)->name('admin.garansi.ubah');

    // Produk
    Route::get('/produk', ProductIndex::class)->name('admin.produk.indeks');
    Route::get('/produk/paket', \App\Livewire\Products\Bundles::class)->name('admin.produk.paket');
    Route::get('/produk/buat', \App\Livewire\Products\Form::class)->name('admin.produk.buat');
    Route::get('/produk/{id}/ubah', \App\Livewire\Products\Form::class)->name('admin.produk.ubah');
    Route::get('/produk/label', \App\Livewire\Products\LabelMaker::class)->name('admin.produk.label');

    // Transaksi
    Route::get('/transaksi', \App\Livewire\Transactions\Index::class)->name('admin.transaksi.indeks');
    Route::get('/transaksi/buat', TransactionCreate::class)->name('admin.transaksi.buat');
    
    // Pengaturan
    Route::get('/pengaturan', \App\Livewire\Settings\Index::class)->name('admin.pengaturan');

    // Data Master
    Route::get('/master/kategori', \App\Livewire\Master\Categories::class)->name('admin.master.kategori');
    Route::get('/master/kategori/buat', \App\Livewire\Master\Categories\Form::class)->name('admin.master.kategori.buat');
    Route::get('/master/kategori/{id}/ubah', \App\Livewire\Master\Categories\Form::class)->name('admin.master.kategori.ubah');
    Route::get('/master/pemasok', \App\Livewire\Master\Suppliers::class)->name('admin.master.pemasok');

    Route::get('/spanduk', \App\Livewire\Banners\Index::class)->name('admin.spanduk.indeks');
    Route::get('/spanduk/buat', \App\Livewire\Banners\Form::class)->name('admin.spanduk.buat');
    Route::get('/spanduk/{id}/ubah', \App\Livewire\Banners\Form::class)->name('admin.spanduk.ubah');

    // Manajemen Karyawan
    Route::get('/karyawan/kehadiran', \App\Livewire\Employees\Attendance::class)->name('admin.karyawan.kehadiran');
    Route::get('/karyawan/pengelola-gaji', \App\Livewire\Employees\PayrollManager::class)->name('admin.karyawan.gaji-pengelola');
    Route::get('/karyawan', \App\Livewire\Employees\Index::class)->name('admin.karyawan.indeks');
    Route::get('/karyawan/peran', \App\Livewire\Admin\RoleManager::class)->name('admin.karyawan.peran.indeks');
    Route::get('/karyawan/peran/buat', \App\Livewire\Admin\RoleForm::class)->name('admin.karyawan.peran.buat');
    Route::get('/karyawan/peran/{id}/ubah', \App\Livewire\Admin\RoleForm::class)->name('admin.karyawan.peran.ubah');
    Route::get('/karyawan/buat', \App\Livewire\Employees\Form::class)->name('admin.karyawan.buat');
    Route::get('/karyawan/{id}/ubah', \App\Livewire\Employees\Form::class)->name('admin.karyawan.ubah');
    Route::get('/gaji', \App\Livewire\Employees\Payroll::class)->name('admin.gaji');
    Route::get('/pengeluaran', \App\Livewire\Expenses\Index::class)->name('admin.pengeluaran');

    // Pelanggan
    Route::get('/pelanggan', \App\Livewire\Customers\Index::class)->name('admin.pelanggan.indeks');
    Route::get('/pelanggan/kotak-masuk', \App\Livewire\Admin\Inbox::class)->name('admin.pelanggan.inbox');
    Route::get('/pelanggan/loyalitas', \App\Livewire\Pelanggan\PengelolaLoyalitas::class)->name('admin.pelanggan.loyalitas');
    Route::get('/pelanggan/obrolan-langsung', \App\Livewire\Admin\LiveChatManager::class)->name('admin.pelanggan.obrolan');
    Route::get('/pelanggan/buat', \App\Livewire\Customers\Form::class)->name('admin.pelanggan.buat');
    Route::get('/pelanggan/{id}', \App\Livewire\Pelanggan\DetailPelanggan::class)->name('admin.pelanggan.tampil');
    Route::get('/pelanggan/{id}/ubah', \App\Livewire\Customers\Form::class)->name('admin.pelanggan.ubah');

    // Log Audit
    Route::get('/log-aktivitas', \App\Livewire\ActivityLogs\Index::class)->name('admin.log-aktivitas.indeks');
    Route::get('/log-aktivitas/{id}', \App\Livewire\ActivityLogs\Show::class)->name('admin.log-aktivitas.tampil');

    // Pusat Servis
    Route::get('/servis/papan', \App\Livewire\Services\Kanban::class)->name('admin.servis.papan');
    Route::get('/servis', \App\Livewire\Services\Index::class)->name('admin.servis.indeks');
    Route::get('/servis/buat', \App\Livewire\Services\Form::class)->name('admin.servis.buat');
    Route::get('/servis/{id}/ubah', \App\Livewire\Services\Form::class)->name('admin.servis.ubah');
    Route::get('/servis/{id}/meja-kerja', \App\Livewire\Services\Workbench::class)->name('admin.servis.meja-kerja');

    // Perakitan PC
    Route::get('/perakitan', \App\Livewire\Assembly\Manager::class)->name('admin.perakitan.indeks');

    // Basis Pengetahuan
    Route::get('/pengetahuan', \App\Livewire\Knowledge\Index::class)->name('admin.pengetahuan.indeks');

    // Permintaan Pembelian
    Route::get('/permintaan-pembelian', \App\Livewire\PurchaseRequisitions\Index::class)->name('admin.permintaan-pembelian.indeks');
    Route::get('/permintaan-pembelian/buat', \App\Livewire\PurchaseRequisitions\Create::class)->name('admin.permintaan-pembelian.buat');
    Route::get('/permintaan-pembelian/{id}', \App\Livewire\PurchaseRequisitions\Show::class)->name('admin.permintaan-pembelian.tampil');

    // Pesanan Pembelian (Pengadaan)
    Route::get('/pesanan-pembelian', \App\Livewire\PurchaseOrders\Index::class)->name('admin.pesanan-pembelian.indeks');
    Route::get('/pesanan-pembelian/terima', \App\Livewire\Procurement\GoodsReceive\Create::class)->name('admin.pesanan-pembelian.terima');
    Route::get('/pesanan-pembelian/buat', \App\Livewire\PurchaseOrders\Form::class)->name('admin.pesanan-pembelian.buat');
    Route::get('/pesanan-pembelian/{id}/ubah', \App\Livewire\PurchaseOrders\Form::class)->name('admin.pesanan-pembelian.ubah');
    Route::get('/pesanan-pembelian/{po}', \App\Livewire\PurchaseOrders\Show::class)->name('admin.pesanan-pembelian.tampil');

    // Pesanan Daring (Penjualan)
    Route::get('/pesanan', \App\Livewire\Orders\Index::class)->name('admin.pesanan.indeks');
    Route::get('/kasir', \App\Livewire\Sales\PointOfSale::class)->name('admin.kasir')->middleware('store.configured');
    Route::get('/pesanan/logistik', \App\Livewire\Logistics\Manager::class)->name('admin.logistik');
    Route::get('/pesanan/{id}', \App\Livewire\Orders\Show::class)->name('admin.pesanan.tampil');

    // Gudang
    Route::get('/gudang/transfer', \App\Livewire\Warehouses\Transfer::class)->name('admin.gudang.transfer');
    Route::get('/gudang/stok-opname', \App\Livewire\Warehouses\StockOpname::class)->name('admin.gudang.stok-opname');

    // Pemasaran (Refactored to Pemasaran Namespace)
    Route::get('/pemasaran/obral-kilat', \App\Livewire\Pemasaran\ObralKilat\Index::class)->name('admin.pemasaran.obral-kilat.indeks');
    Route::get('/pemasaran/obral-kilat/buat', \App\Livewire\Pemasaran\ObralKilat\Form::class)->name('admin.pemasaran.obral-kilat.buat');
    Route::get('/pemasaran/ulasan', \App\Livewire\Pemasaran\Ulasan\Pengelola::class)->name('admin.pemasaran.ulasan');
    Route::get('/pemasaran/voucher', \App\Livewire\Pemasaran\Voucher\Index::class)->name('admin.pemasaran.voucher.indeks');
    Route::get('/pemasaran/voucher/buat', \App\Livewire\Pemasaran\Voucher\Form::class)->name('admin.pemasaran.voucher.buat');
    Route::get('/pemasaran/whatsapp', \App\Livewire\Pemasaran\PesanMassalWhatsapp::class)->name('admin.pemasaran.whatsapp');

    // Manajemen Berita
    Route::get('/berita', \App\Livewire\News\Index::class)->name('admin.berita.indeks');
    Route::get('/berita/buat', \App\Livewire\News\Form::class)->name('admin.berita.buat');
    Route::get('/berita/{id}/ubah', \App\Livewire\News\Form::class)->name('admin.berita.ubah');

    // Cetak
    Route::get('/cetak/transaksi/{id}', [\App\Http\Controllers\PrintController::class, 'transaction'])->name('admin.cetak.transaksi');
    Route::get('/cetak/servis/{id}', [\App\Http\Controllers\PrintController::class, 'service'])->name('admin.cetak.servis');
    Route::get('/cetak/label/{id}', [\App\Http\Controllers\PrintController::class, 'productLabel'])->name('admin.cetak.label');
    Route::get('/cetak/label-masal', [\App\Http\Controllers\PrintController::class, 'labels'])->name('admin.cetak.label-masal');

    // Manajemen Aset
    Route::get('/aset', \App\Livewire\Assets\Index::class)->name('admin.aset.indeks');
    Route::get('/aset/buat', \App\Livewire\Assets\Form::class)->name('admin.aset.buat');

    // Analitik & HR
    Route::get('/analitik', \App\Livewire\Reports\Index::class)->name('admin.analitik.indeks');
    Route::get('/analitik/keuangan', \App\Livewire\Reports\FinanceReport::class)->name('admin.analitik.keuangan');
    Route::get('/analitik/stok', \App\Livewire\Reports\StockReport::class)->name('admin.analitik.stok');
    Route::get('/analitik/penjualan', \App\Livewire\Reports\SalesReport::class)->name('admin.analitik.penjualan');

    Route::get('/shift', \App\Livewire\Shift\Manager::class)->name('admin.shift');
    Route::get('/reimbursement', \App\Livewire\Employees\Reimbursement::class)->name('admin.reimbursement');

    // B2B & Keuangan
    Route::get('/penawaran', \App\Livewire\Quotations\Index::class)->name('admin.penawaran.indeks');
    Route::get('/penawaran/buat', \App\Livewire\Quotations\Form::class)->name('admin.penawaran.buat');
    Route::get('/penawaran/{id}/ubah', \App\Livewire\Quotations\Form::class)->name('admin.penawaran.ubah');
    Route::get('/keuangan/kasir', \App\Livewire\Finance\CashRegisterManager::class)->name('admin.keuangan.kasir');
    Route::get('/keuangan/piutang', \App\Livewire\Finance\Receivables::class)->name('admin.keuangan.piutang');
    Route::get('/keuangan/laba-rugi', \App\Livewire\Finance\ProfitLoss::class)->name('admin.keuangan.laba-rugi');

    // Pemeliharaan Sistem
    Route::get('/sistem/kesehatan', \App\Livewire\System\Health::class)->name('admin.sistem.kesehatan');
    Route::get('/sistem/info', \App\Livewire\System\Info::class)->name('admin.sistem.info');
    Route::get('/sistem/cadangan', \App\Livewire\System\Backups::class)->name('admin.sistem.cadangan');
});