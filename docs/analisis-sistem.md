# Analisis Sistem Menyeluruh - Yala Computer

## Kondisi Sistem Saat Ini (Baseline)
Audit dilakukan pada 27 Januari 2026.

### 1. Struktur Arsitektur
- **Backend**: Laravel 12, Eloquent ORM.
- **Frontend**: Livewire 4, Tailwind CSS 4, Alpine.js.
- **Modul**: Terdapat >30 modul fungsional yang mencakup seluruh aspek operasional bisnis.

### 2. Temuan Audit: Admin / Operasional
- **Inkonsistensi Bahasa**:
    - Nama rute masih menggunakan Bahasa Inggris (contoh: `products.index`, `sales.pos`).
    - Penamaan folder dan class Livewire didominasi Bahasa Inggris (contoh: `app/Livewire/PurchaseOrders`).
    - Variabel internal banyak yang masih menggunakan Bahasa Inggris.
- **Redundansi Modul**:
    - Terdapat folder `app/Livewire/Customers` dan `app/Livewire/Pelanggan`. Perlu konsolidasi.
- **Integrasi Menu**:
    - Menu CRM masih terpisah dari Media & Customer Service (instruksi baru meminta penggabungan).
- **Fungsionalitas**:
    - Fitur "Kasir & Penjualan" dan "Gudang & Logistik" terlihat kompleks di kode, namun perlu verifikasi integrasi end-to-end dengan Storefront.

### 3. Temuan Audit: Storefront
- **Tampilan**: Komponen UI sudah lengkap (Home, Catalog, Detail, Cart, Checkout).
- **Interaksi**: Fitur simulasi rakit PC dan obrolan sudah ada.
- **Sinkronisasi**: Perlu verifikasi apakah perubahan stok di Admin langsung tercermin di Storefront secara akurat.

### 4. Daftar Bug & Kendala
- **Rute Mati**: Perubahan nama rute sebelumnya kemungkinan meninggalkan tautan mati di view.
- **Validasi**: Beberapa form mungkin belum menggunakan pesan error Bahasa Indonesia yang konsisten.
- **Modal**: Instruksi melarang penggunaan layout modal, perlu cek apakah ada komponen yang masih menggunakannya.

---

## Rencana Perbaikan (Iteratif)

### Tahap A: Standarisasi Bahasa & Struktur (Prioritas Utama)
1. **Refaktor Rute**: Mengubah seluruh nama rute di `routes/web.php` ke Bahasa Indonesia.
2. **Refaktor Navigasi**: Memperbarui Sidebar dan Tautan di seluruh View agar sesuai rute baru.
3. **Penggabungan Menu**: Menyatukan CRM/Pelanggan ke dalam grup "Media & Hubungan Pelanggan".

### Tahap B: Penguatan Fitur Operasional
1. **Sistem & Pengaturan**: Melengkapi logika pengaturan sistem agar lebih kompleks dan menyeluruh.
2. **Kasir & Stok**: Memastikan transaksi kasir memotong stok gudang secara real-time dan masuk ke laporan keuangan.
3. **SDM**: Verifikasi perhitungan gaji otomatis berdasarkan absensi.

### Tahap C: Penyempurnaan Storefront
1. **UX**: Menghapus sisa-sisa elemen bahasa asing.
2. **Integrasi**: Memastikan alur pesanan dari Storefront masuk dengan status yang benar ke Admin Pesanan.

### Tahap D: Validasi & Finalisasi
1. Pengujian seluruh tombol dan alur data.
2. Pembersihan kode dari komentar bahasa asing.