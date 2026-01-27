# Analisis Sistem Menyeluruh - Yala Computer

## Kondisi Sistem Saat Ini (Baseline)
Audit dilakukan pada 27 Januari 2026.

### 1. Kepatuhan Bahasa (Aturan Global)
- **Frontend**: Sebagian besar UI sudah menggunakan Bahasa Indonesia, namun masih ditemukan istilah teknis seperti "Activity Logs", "Inventory", "Procurement" pada sidebar atau menu admin.
- **Backend**: 
    - Nama folder dan class masih didominasi Bahasa Inggris (misal: `app/Livewire/PurchaseOrders`).
    - Variabel internal dan parameter fungsi masih banyak menggunakan Bahasa Inggris (misal: `$filterUserType`).
    - Komentar kode mulai beralih ke Bahasa Indonesia namun belum konsisten 100%.
- **Validasi & Error**: Pesan error Laravel default mungkin masih dalam Bahasa Inggris.

### 2. Area Admin / Operasional
- **Dashboard**: Berfungsi, namun integrasi data antar modul (Gudang ↔ Keuangan) perlu divalidasi keakuratannya secara real-time.
- **Kasir & Penjualan**: Modul POS sudah ada, namun integrasi dengan modul "Loyalitas Pelanggan" perlu diperdalam.
- **Gudang & Logistik**: Struktur folder sudah lengkap, namun alur "Permintaan Stok" hingga "Penerimaan Barang" perlu pengujian end-to-end.
- **SDM & Karyawan**: Modul `Employees` sudah ada, namun fitur "Penggajian" (Payroll) terlihat masih dasar dan belum mencakup komponen kompleks (bonus, denda, pajak).
- **Media & Customer Service**: CRM sudah digabung ke Media sesuai instruksi sebelumnya, namun fitur "WhatsApp Blast" perlu dipastikan kestabilannya.

### 3. Area Storefront
- **UX/UI**: Desain sudah menggunakan tema "Cyberpunk/Tech", namun navigasi seluler perlu ditingkatkan.
- **Integrasi**: Alur checkout Midtrans harus dipastikan sinkron dengan status pesanan di Admin secara otomatis.
- **Validasi**: Validasi input form pendaftaran dan alamat perlu disesuaikan dengan standar Indonesia (misal: format nomor HP).

### 4. Daftar Temuan Bug & Inkonsistensi
- **Bug Rute**: Beberapa rute sebelumnya mengalami `RouteNotFoundException` karena inkonsistensi penamaan antara `web.php` dan View (telah diperbaiki di baseline).
- **Inkonsistensi Folder**: Folder `Customers` dan `Pelanggan` sempat tumpang tindih, perlu konsolidasi total ke Bahasa Indonesia.
- **Menu Kosong**: Ada kemungkinan beberapa sub-menu di sidebar belum memiliki view yang matang.

---

## Rencana Perbaikan Iteratif

1. **Checkpoint Refaktor Bahasa (Backend)**: Mengubah nama variabel, fungsi, dan folder secara bertahap tanpa merusak fungsionalitas.
2. **Penyempurnaan Modul SDM**: Menambah kompleksitas sistem payroll dan absensi.
3. **Penyempurnaan Modul Gudang**: Integrasi otomatis stok opname dengan laporan kerugian.
4. **Validasi End-to-End Storefront**: Pengujian alur belanja dari tamu hingga menjadi member loyal.
