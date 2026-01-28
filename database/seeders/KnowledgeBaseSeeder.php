<?php

namespace Database\Seeders;

use App\Models\KnowledgeArticle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KnowledgeBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada user admin untuk author
        $admin = User::first() ?? User::factory()->create();

        $articles = [
            [
                'title' => 'Panduan Lengkap Merakit PC Gaming High-End (2025 Edition)',
                'category' => 'Perakitan PC',
                'content' => "
# Pendahuluan
Merakit PC gaming high-end bukan hanya soal menggabungkan komponen mahal, tetapi memastikan sinergi antar komponen untuk performa maksimal tanpa bottleneck. Panduan ini akan membahas secara mendalam setiap aspek, mulai dari pemilihan komponen hingga manajemen kabel dan optimasi BIOS.

## 1. Pemilihan Komponen Utama (The Big Three)

### A. Processor (CPU)
Untuk gaming 4K dan streaming, pilihan utama jatuh pada:
- **Intel Core i9-14900K**: Raja single-core performance, ideal untuk gaming kompetitif frame-rate tinggi.
- **AMD Ryzen 9 7950X3D**: Efisiensi daya luar biasa dengan teknologi 3D V-Cache yang memberikan boost FPS signifikan pada game tertentu.

**Tips:** Pastikan motherboard Anda memiliki VRM (Voltage Regulator Module) yang cukup kuat (minimal 16+1+2 phase) untuk menangani daya CPU ini.

### B. Kartu Grafis (GPU)
Jantung dari PC gaming.
- **NVIDIA RTX 4090**: Monster 24GB VRAM. Wajib untuk Ray Tracing rata kanan di resolusi 4K.
- **AMD RX 7900 XTX**: Alternatif value-for-money untuk performa rasterization murni.

### C. Motherboard
Jangan pelit di sini. Pilih chipset Z790 (Intel) atau X670E (AMD). Fitur wajib:
- PCIe 5.0 Slot untuk GPU dan SSD masa depan.
- Dukungan RAM DDR5 6000MHz+.
- Konektivitas LAN 2.5G/10G dan Wi-Fi 7.

## 2. Sistem Pendingin (Thermal Management)
Panas adalah musuh performa.
- **AIO Liquid Cooler 360mm**: Wajib untuk CPU high-end. Merek rekomendasi: NZXT Kraken, Corsair H150i, Lian Li H150.
- **Airflow Casing**: Pilih casing dengan mesh front panel (Lian Li O11 Dynamic EVO atau Fractal North) dan atur konfigurasi kipas Intake (Depan/Bawah) > Exhaust (Belakang/Atas) untuk tekanan udara positif.

## 3. Storage & Memory
- **RAM**: DDR5 32GB (2x16GB) 6000MHz CL30 adalah sweet spot saat ini.
- **SSD**: NVMe Gen4 (Samsung 990 Pro / WD Black SN850X). Kecepatan baca 7000MB/s+ mengurangi loading time game secara drastis.

## 4. Langkah-Langkah Perakitan (Step-by-Step)

1.  **Bench Test**: Pasang CPU, RAM, dan GPU di motherboard di atas kardus sebelum masuk casing. Nyalakan untuk memastikan POST (Power On Self Test).
2.  **Instalasi Motherboard**: Pasang I/O shield (jika tidak terintegrasi) dan stand-off screw sesuai ukuran motherboard.
3.  **Manajemen Kabel**:
    *   Pasang PSU terlebih dahulu.
    *   Rutekan kabel CPU 8-pin lewat lubang pojok kiri atas *sebelum* motherboard dipasang jika casing sempit.
    *   Gunakan velcro strap, bukan cable ties plastik agar mudah diubah.

## 5. Pasca-Rakit & Optimasi
- **Update BIOS**: Versi terbaru seringkali meningkatkan stabilitas RAM DDR5.
- **XMP/EXPO**: Wajib diaktifkan di BIOS agar RAM berjalan di kecepatan iklan (bukan default 4800MHz).
- **Fan Curve**: Atur kurva kipas di BIOS agar hening saat idle dan agresif saat gaming.

---
*Ditulis oleh Tim Teknis Yala Computer. Hubungi kami jika Anda membutuhkan jasa perakitan profesional.*
",
            ],
            [
                'title' => 'Troubleshooting: PC Nyala Tapi Tidak Ada Tampilan (No Display)',
                'category' => 'Troubleshooting',
                'content' => "
Masalah 'No Display' adalah mimpi buruk setiap perakit PC. Kipas berputar, lampu menyala, tapi monitor gelap. Jangan panik, ikuti panduan diagnosis sistematis ini.

## Diagnosa Awal (Level 1)
1.  **Cek Kabel Monitor**: Pastikan kabel HDMI/DisplayPort tercolok ke **GPU**, BUKAN ke Motherboard (kecuali Anda menggunakan iGPU/APU).
2.  **Input Source**: Pastikan monitor diatur ke input yang benar (HDMI 1, DP, dll).
3.  **Reseat RAM**: Lepas dan pasang kembali RAM. Tekan hingga bunyi 'klik'. Coba gunakan satu keping RAM di slot yang berbeda secara bergantian.

## Diagnosa Lanjut (Level 2)
Jika Level 1 gagal, perhatikan **Debug LED** di motherboard (biasanya di pojok kanan atas):
- **CPU (Merah)**: Masalah pada prosesor atau soket motherboard (pin bengkok).
- **DRAM (Kuning/Oranye)**: Masalah memori. Coba tombol 'MemOK!' jika ada, atau reset CMOS.
- **VGA (Putih)**: GPU tidak terdeteksi. Cek kabel power PCIe (pastikan tercolok penuh) dan slot PCIe.
- **BOOT (Hijau)**: Masalah drive penyimpanan.

## Reset CMOS (The Magic Fix)
Seringkali pengaturan BIOS yang korup menyebabkan no display.
1.  Matikan PSU dan cabut kabel power.
2.  Lepas baterai koin (CR2032) di motherboard.
3.  Tunggu 5 menit.
4.  Pasang kembali dan nyalakan.

## Diagnosa Hardware (Level 3 - Wajib Punya Sparepart)
- **Tes PSU**: Cek tegangan dengan multimeter atau PSU tester.
- **Ganti GPU**: Coba GPU lain yang diketahui berfungsi.
- **Cek Pin CPU**: Bongkar cooler, lepas CPU, dan gunakan kaca pembesar/kamera HP untuk melihat apakah ada pin soket motherboard yang bengkok (untuk Intel) atau pin CPU yang patah (untuk AMD AM4 ke bawah).

## Kapan Harus Ke Servis?
Jika Anda sudah mencoba reset CMOS, tukar RAM, dan cek kabel tapi tetap nihil, kemungkinan ada kerusakan fisik pada Motherboard atau CPU. Bawa ke Yala Computer Service Center untuk diagnosis mendalam menggunakan kartu diagnostik POST.
",
            ],
            [
                'title' => 'Perbedaan Panel Monitor: TN vs IPS vs VA vs OLED',
                'category' => 'Panduan Belanja',
                'content' => "
Memilih monitor bukan hanya soal ukuran dan resolusi. Jenis panel menentukan kualitas warna, sudut pandang, dan responsivitas. Berikut perbandingan mendalamnya.

## 1. TN (Twisted Nematic)
- **Kelebihan**: Response time super cepat (bisa < 1ms), harga paling murah, refresh rate tinggi mudah dicapai.
- **Kekurangan**: Warna pudar (washed out), sudut pandang (viewing angle) sangat buruk (warna berubah jika dilihat dari samping/bawah).
- **Cocok Untuk**: Pemain e-Sports kompetitif (CS:GO, Valorant) yang mengutamakan kecepatan di atas segalanya dan budget terbatas.

## 2. IPS (In-Plane Switching)
- **Kelebihan**: Akurasi warna terbaik, sudut pandang sangat luas (178 derajat), konsistensi warna tinggi.
- **Kekurangan**: Kontras rendah (hitam terlihat abu-abu gelap), potensi 'IPS Glow' (cahaya bocor di pojok layar).
- **Cocok Untuk**: Desainer grafis, editor video, gamer AAA yang ingin visual indah, penggunaan umum. **Ini adalah standar emas monitor modern.**

## 3. VA (Vertical Alignment)
- **Kelebihan**: Rasio kontras terbaik di kelas LCD (hitam sangat pekat), warna lebih hidup daripada TN.
- **Kekurangan**: Response time lambat (sering terjadi 'ghosting' atau bayangan hitam pada objek bergerak cepat), sudut pandang tidak sebaik IPS.
- **Cocok Untuk**: Menonton film di ruangan gelap, game single-player dengan suasana gelap (Horror, RPG).

## 4. OLED (Organic Light-Emitting Diode)
- **Kelebihan**: Kontras tak terbatas (True Black karena piksel mati total), response time instan (0.03ms), warna sangat vivid.
- **Kekurangan**: Risiko Burn-in (gambar berbayang permanen jika menampilkan gambar statis terlalu lama), brightness maksimal biasanya lebih rendah dari Mini-LED, harga sangat mahal.
- **Cocok Untuk**: Sultan, penikmat konten HDR, gamer yang menginginkan kualitas gambar terbaik tanpa kompromi.

## Tabel Perbandingan Cepat

| Fitur | TN | IPS | VA | OLED |
| :--- | :--- | :--- | :--- | :--- |
| **Warna** | Buruk | **Sangat Baik** | Baik | **Sempurna** |
| **Kontras** | Rendah | Sedang | **Tinggi** | **Sempurna** |
| **Speed** | **Sangat Cepat** | Cepat | Lambat | **Instan** |
| **Angle** | Buruk | **Luas** | Sedang | **Luas** |
| **Harga** | Murah | Sedang/Mahal | Sedang | Mahal |

**Rekomendasi Yala:** Untuk tahun 2025, kami sangat menyarankan panel **Fast IPS** untuk keseimbangan terbaik antara kecepatan dan kualitas gambar bagi 90% pengguna.
",
            ],
            [
                'title' => 'Cara Merawat Laptop Agar Awet 5 Tahun Lebih',
                'category' => 'Maintenance',
                'content' => "
Laptop adalah investasi jangka panjang. Dengan perawatan yang tepat, performa laptop bisa tetap prima hingga 5 tahun atau lebih.

## 1. Manajemen Baterai (Penting!)
Baterai Li-ion memiliki siklus hidup terbatas.
- **Jangan biarkan 0%**: Sering membiarkan baterai habis total akan merusak sel baterai (deep discharge).
- **Batas Cas 80%**: Jika laptop sering dicolok (plugged in), aktifkan fitur 'Battery Conservation Mode' (tersedia di software Asus, Lenovo, Dell) yang membatasi pengisian hingga 60% atau 80%. Ini memperpanjang umur baterai secara signifikan.
- **Suhu**: Panas membunuh baterai. Jangan cas laptop di kasur atau bantal yang menghambat sirkulasi udara.

## 2. Kebersihan Fisik
- **Layar**: Gunakan kain microfiber dan cairan khusus pembersih layar. Jangan semprot cairan langsung ke layar, semprot ke kain dulu.
- **Keyboard**: Debu dan remah makanan bisa membuat tombol macet. Gunakan kuas halus atau compressed air kalengan seminggu sekali.
- **Sirkulasi Udara**: Debu yang menumpuk di heatsink menyebabkan overheat. Lakukan pembersihan internal (bongkar casing dan bersihkan kipas) minimal setahun sekali.

## 3. Software Health
- **Bloatware**: Hapus aplikasi bawaan pabrik yang tidak berguna.
- **SSD Trim**: Lakukan optimasi SSD (Trim) sebulan sekali (Windows biasanya melakukan ini otomatis, tapi pastikan aktif).
- **Update Driver**: Selalu update driver GPU dan BIOS dari situs resmi produsen untuk perbaikan bug dan keamanan.

## 4. Engsel (Hinge) Laptop
Ini adalah bagian mekanis paling rentan.
- **Buka dari tengah**: Jangan membuka layar dari pojok kiri/kanan atas, ini memberikan tekanan tidak seimbang pada engsel. Buka selalu dari tengah webcam.
- **Jangan tutup paksa**: Pastikan tidak ada pulpen atau flashdisk tertinggal di atas keyboard sebelum menutup layar.

## 5. Repaste Thermal Paste
Setelah 2-3 tahun, pasta pendingin prosesor akan mengering dan keras, menyebabkan suhu melonjak. Mengganti thermal paste dengan kualitas tinggi (seperti Arctic MX-4 atau Thermal Grizzly) bisa menurunkan suhu hingga 10-15 derajat celcius! Layanan ini tersedia di Yala Computer.
",
            ],
        ];

        foreach ($articles as $article) {
            KnowledgeArticle::updateOrCreate(
                ['title' => $article['title']],
                [
                    'slug' => Str::slug($article['title']),
                    'category' => $article['category'],
                    'content' => $article['content'],
                    'author_id' => $admin->id,
                    'is_published' => true,
                ]
            );
        }
    }
}