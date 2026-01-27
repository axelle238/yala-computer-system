# Analisis Sistem - Yala Computer System

Dibuat pada: Selasa, 27 Januari 2026

## 1. Area Admin / Operasional
### Temuan Positif:
- Struktur navigasi sudah terorganisir dengan baik.
- Layout `admin.blade.php` sudah menggunakan Bahasa Indonesia untuk sebagian besar elemen UI.
- Penggunaan Livewire v4 memberikan interaktivitas yang baik.
- Dashboard sudah memiliki metrik utama yang relevan (Pendapatan, Laba, Servis, Pesan).

### Masalah & Inkonsistensi (Bahasa):
- **Metode Model:** Masih banyak menggunakan Bahasa Inggris (contoh: `serials()`, `category()`, `supplier()`, `transactions()` di `Product.php`).
- **Status Database:** Nilai status masih menggunakan Bahasa Inggris (`pending`, `completed`, `shipped`, dll). Meskipun nilai DB bisa tetap Inggris, label UI harus selalu Indonesia.
- **Komentar Kode:** Sebagian besar sudah Indonesia, namun masih ada sisa-sisa Bahasa Inggris di beberapa file (terutama migrasi dan model lama).
- **Komponen UI:** Nama komponen seperti `Spotlight` dan istilah seperti `Enterprise` di footer perlu diindonesiakan.
- **Label Status:** Di beberapa halaman (seperti `quotations/index.blade.php` dan `orders/index.blade.php`), label status masih menampilkan teks Inggris seperti "Pending Review" atau "Pending Payment".

### Fitur Setengah Jadi / Bug:
- **Validasi CRUD:** Perlu dipastikan semua formulir memiliki validasi yang lengkap dengan pesan error Bahasa Indonesia.
- **Notifikasi:** Beberapa aksi mungkin belum memicu notifikasi `success` atau `error` yang konsisten.

---

## 2. Area Storefront (Halaman Pengguna)
### Temuan Positif:
- Tampilan modern dengan tema gelap (Cyberpunk style).
- Integrasi Midtrans sudah disiapkan.
- Navigasi utama sudah menggunakan Bahasa Indonesia.

### Masalah & Inkonsistensi:
- **Logika Frontend:** Beberapa variabel di JavaScript (terutama integrasi Midtrans) menggunakan istilah Inggris (ini standar API, tapi bisa diberi komentar Indonesia).
- **Pesan Alert:** Masih ditemukan alert Bahasa Inggris (contoh di `checkout.blade.php`: `alert('Anda menutup popup...')` - ini sudah Indonesia, tapi perlu dicek di tempat lain).
- **Status Pelacakan:** Di halaman `lacak-pesanan` dan `lacak-servis`, pemetaan status harus dipastikan 100% Indonesia.

---

## 3. Daftar Inkonsistensi Frontend-Backend
- Hubungan Eloquent (Relationships) di model menggunakan Bahasa Inggris, sementara di view terkadang dipanggil dengan ekspektasi Bahasa Indonesia atau sebaliknya.
- Konstanta status di model (contoh: `STATUS_BARU` di `PesanPelanggan`) sudah bagus, perlu diterapkan ke semua model.

---

## 4. Rencana Tindakan (Iterasi)
1. **Iterasi 1:** Standarisasi Bahasa Backend (Model & Variabel). Mengubah nama metode relationship menjadi Bahasa Indonesia.
2. **Iterasi 2:** Standarisasi Label Status di UI. Pastikan semua status Inggris di DB dipetakan ke label Indonesia di View.
3. **Iterasi 3:** Perbaikan Notifikasi & Validasi. Memastikan seluruh alur CRUD memiliki feedback yang jelas.
4. **Iterasi 4:** Pembersihan Komentar & Istilah Sisa. Menghapus istilah Inggris yang tersisa di footer, placeholder, dan komentar.
5. **Iterasi 5:** Validasi End-to-End untuk Admin dan Storefront.

---
**Status Audit:** Selesai.
**Rekomendasi:** Lanjutkan ke pembersihan metode model sebagai prioritas pertama untuk sinkronisasi backend.
