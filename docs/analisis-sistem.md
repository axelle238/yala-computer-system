# Analisis Sistem - Yala Computer (Revisi: 26 Januari 2026 - Fase 2)

## Pendahuluan
Dokumen ini memperbarui hasil analisis sebelumnya dengan fokus pada transformasi ke "Sistem V3" dan restrukturisasi navigasi Storefront serta modul Media & CS.

## Temuan & Rencana Perubahan

### A. Storefront (Navigasi & Layout)
1.  **Navbar Utama:**
    *   **Saat Ini:** Katalog, Merek, Rakit PC, Servis.
    *   **Perubahan:** 
        *   Hapus: "Merek", "Rakit PC", "Servis".
        *   Tambah: "Berita".
        *   Pindahkan "Merek", "Rakit PC", "Servis" ke Dropdown Menu Pelanggan (User Menu).
2.  **Footer:**
    *   **Saat Ini:** Ada link "Lacak Pesanan" dan "Berita".
    *   **Perubahan:** Hapus link tersebut. "Lacak Pesanan" pindah ke Dropdown Menu Pelanggan.
3.  **Dropdown Pelanggan:**
    *   Akan menjadi pusat navigasi fitur fungsional (Rakit PC, Lacak Service, Lacak Pesanan, Merek).

### B. Admin / Operasional (Sistem V3)
1.  **Layout:**
    *   Perlu redesign `layouts/admin.blade.php` agar lebih "High-End Technology".
    *   Penggunaan warna gradasi, glassmorphism, dan font modern (Inter/Exo 2 sudah bagus, perlu tuning CSS).
2.  **Modul "Media dan Customer Service":**
    *   **Berita & Artikel:** Sudah ada (`admin.news`).
    *   **Banner:** Sudah ada (`admin.banners`).
    *   **Inbox/Chat:** Sudah ada (`admin.customers.inbox`), perlu dipastikan live sync.
    *   **Customer Service:** Perlu menu eksplisit yang menggabungkan Inbox dan Tiket Servis jika memungkinkan, atau penamaan ulang.

### C. Kepatuhan Bahasa
*   Pemeriksaan ulang label menu dan tombol untuk memastikan 100% Bahasa Indonesia baku.

## Daftar Tugas (Prioritas)

1.  **Restrukturisasi Navigasi Store:** Ubah `layouts/store.blade.php`.
2.  **Implementasi Modul Media & CS:** Pastikan Controller dan View siap dan dalam grup menu yang benar di Sidebar.
3.  **Redesign Admin V3:** Update `layouts/admin.blade.php` dan `dashboard.blade.php`.
4.  **Redesign Store V3:** Update `layouts/store.blade.php` (Visual) dan `home.blade.php`.

---
*Dibuat oleh: Gemini CLI*
