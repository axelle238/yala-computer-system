# Analisis Sistem - Yala Computer

## Pendahuluan
Dokumen ini berisi hasil analisis menyeluruh terhadap sistem Yala Computer, mencakup area Admin/Operasional dan Storefront. Analisis dilakukan untuk memastikan kesiapan sistem dalam proses "Indonesinisasi" total sesuai aturan proyek.

## Temuan Utama

### A. Admin & Operasional
1.  **Routing:** Struktur routing sudah sangat lengkap mencakup POS, Manajemen Stok, Layanan Servis, CRM, Keuangan, dan SDM. Namun, sebagian besar nama route dan prefix masih menggunakan Bahasa Inggris (contoh: `/admin/purchase-orders`).
2.  **Antarmuka (UI):** Sidebar dan Header sudah menggunakan Bahasa Indonesia untuk label utama, namun masih terdapat istilah teknis Inggris seperti "Integrated System", "Front Office", "CRM", "HRM".
3.  **Logika Backend:**
    *   **Nama Class & Method:** Hampir seluruhnya masih dalam Bahasa Inggris (contoh: `Dashboard::render()`, `BusinessIntelligence::getProfitLoss()`).
    *   **Variabel:** Penggunaan variabel internal masih didominasi Bahasa Inggris.
    *   **Pesan Notifikasi:** Sebagian sudah Bahasa Indonesia, namun validasi bawaan Laravel kemungkinan masih Inggris.

### B. Storefront (Halaman Pengguna)
1.  **Alur Pengguna:** Alur dari Katalog -> Produk -> Keranjang -> Checkout sudah tersedia.
2.  **Integrasi:** Frontend dan Backend sudah terhubung (Livewire).
3.  **Bahasa:** Teks UI di Storefront lebih banyak menggunakan Bahasa Indonesia dibanding area Admin, namun beberapa placeholder dan pesan error masih perlu diperbaiki.

### C. Basis Data & Model
1.  **Skema Database:** Seluruh tabel dan kolom menggunakan Bahasa Inggris (contoh: `products`, `stock_quantity`, `buy_price`).
2.  **Relasi Eloquent:** Nama fungsi relasi masih Inggris (contoh: `Product::category()`).

## Rencana Perbaikan (Indonesinisasi)

### Tahap 1: Pembersihan Komentar & Teks UI
*   Mengubah seluruh komentar kode ke Bahasa Indonesia.
*   Memastikan 100% teks yang muncul di browser adalah Bahasa Indonesia.

### Tahap 2: Refactoring Backend (Bertahap)
*   Mengubah nama variabel dalam fungsi.
*   Mengubah nama method secara hati-hati (1 commit per perubahan logis).
*   Mengubah nama Class/Model.

### Tahap 3: Migrasi Basis Data
*   Melakukan migrasi untuk mengubah nama kolom tabel ke Bahasa Indonesia (contoh: `buy_price` -> `harga_beli`). *Catatan: Ini adalah perubahan berisiko tinggi dan akan dilakukan dengan checkpoint yang ketat.*

## Kesimpulan
Sistem secara fungsional sudah sangat kaya fitur, namun secara bahasa masih sangat "Inggris-sentris" di level kode. Diperlukan upaya refactoring besar-besaran untuk mematuhi aturan "Bahasa Indonesia 100%".

---
*Dibuat oleh: Gemini CLI*
*Tanggal: 25 Januari 2026*
