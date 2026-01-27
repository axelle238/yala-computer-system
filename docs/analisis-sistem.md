# Analisis Sistem Menyeluruh - Yala Computer
Tanggal Audit: 27 Januari 2026
Status: Baseline (Sebelum Refaktor Besar)

## 1. Temuan Struktural & Arsitektur
Audit struktur folder `app/Livewire` menunjukkan fragmentasi bahasa dan logika:

### Inkonsistensi Namespace & Bahasa
- **Ganda/Ambigu**:
    - `Customers` (CRUD Dasar) vs `Pelanggan` (Fitur Detail & Loyalitas). Perlu disatukan.
    - `Service` (Booking Member) vs `Services` (Manajemen Admin). Perlu disatukan ke `Layanan` atau `Servis`.
    - `Front` (Tracking) vs `Store` (Logika Utama Toko).
- **Campuran Bahasa**:
    - Folder `Pemasaran` (ID) berdampingan dengan `Products` (EN), `Sales` (EN).
    - Aturan mewajibkan 100% Bahasa Indonesia. Ini memerlukan refaktor namespace yang hati-hati karena akan mengubah path class di seluruh file blade dan route.

## 2. Status Fungsional Admin / Operasional
### Kelebihan
- Modul inti (Produk, Transaksi, Laporan) sudah tersedia secara struktural.
- Fitur "Rakitan PC" (`Assembly`) dan "Papan Kanban Servis" menunjukkan kompleksitas yang baik.

### Kekurangan / Area Perbaikan
- **Validasi**: Perlu pengecekan apakah pesan validasi (request validation) sudah diterjemahkan ke Bahasa Indonesia di `lang/id`.
- **Integrasi**: Hubungan antara `Customers` (data dasar) dan `Pelanggan` (loyalty) perlu diperiksa. Apakah `DetailPelanggan` bisa mengakses data `Index` di `Customers`?

## 3. Status Fungsional Storefront
- Fitur Toko cukup lengkap: Katalog, Detail Produk, Rakit PC, Cek Garansi.
- Perlu dipastikan seluruh label tombol, pesan sukses (flash message), dan modal konfirmasi menggunakan Bahasa Indonesia yang baku dan sopan.

## 4. Rencana Aksi Perbaikan (Roadmap)

### Fase 1: Konsistensi UI & Bahasa (Prioritas Utama)
- Memastikan **TAMPILAN** (Blade views) menggunakan 100% Bahasa Indonesia.
- Mengubah label menu, tombol, header tabel, dan notifikasi.
- **Strategi**: Tidak mengubah nama Class/File PHP dulu untuk menghindari error massal, tapi mengubah teks outputnya.

### Fase 2: Konsolidasi Logika Frontend-Backend
- Memastikan form di Admin (misal: Tambah Produk) menyimpan data dengan benar.
- Memastikan data yang tampil di Storefront sinkron dengan Admin.

### Fase 3: Refaktor Struktural (Opsional/Bertahap)
- Jika stabilitas terjaga, pelan-pelan migrasi file `Customers` ke `Pelanggan`, dsb.
- *Catatan: Mengubah nama folder Livewire membutuhkan update di semua view yang memanggil komponen tersebut (`<livewire:customers.index />` -> `<livewire:pelanggan.index />`).*

## Daftar Bug/Isu Kritis (Saat Ini)
1. **Navigasi**: Menu di `config/nav.php` harus dicek apakah `route` yang dipanggil benar-benar ada (sudah diperbaiki satu di baseline: `products.labels`).
2. **Duplikasi Konsep**: Potensi kebingungan pengembang selanjutnya karena folder ganda (`Service` vs `Services`).

## Kesimpulan
Sistem dalam kondisi "Berjalan tapi Terfragmentasi". Fokus utama adalah standarisasi Bahasa Indonesia pada lapisan presentasi (View) dan menyatukan fitur yang terpecah agar pengalaman pengguna (UX) dan pengembang (DX) lebih mulus.