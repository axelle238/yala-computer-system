<?php

namespace Database\Seeders;

use App\Models\KnowledgeArticle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first() ?? User::factory()->create();

        $articles = [
            [
                'title' => 'SOP Penerimaan Barang Servis',
                'category' => 'SOP Operasional',
                'content' => '
                    <h2>1. Penerimaan Unit</h2>
                    <p>Saat pelanggan datang, teknisi atau front desk wajib:</p>
                    <ul>
                        <li>Menanyakan keluhan utama secara detail.</li>
                        <li>Memeriksa kondisi fisik unit (lecet, retak, baut hilang) dan mencatatnya di form penerimaan.</li>
                        <li>Memastikan data penting pelanggan sudah di-backup jika memungkinkan.</li>
                    </ul>
                    <h2>2. Estimasi Biaya & Waktu</h2>
                    <p>Berikan kisaran biaya awal, namun tegaskan bahwa biaya pasti baru diketahui setelah pengecekan mendalam (diagnosa).</p>
                    <h2>3. Tanda Terima</h2>
                    <p>Wajib mencetak tanda terima tiket servis dan meminta tanda tangan pelanggan.</p>
                ',
            ],
            [
                'title' => 'Panduan Merakit PC Gaming High-End',
                'category' => 'Teknis & Perakitan',
                'content' => '
                    <p>Merakit PC High-End membutuhkan ketelitian ekstra. Berikut langkah kuncinya:</p>
                    <h3>1. Persiapan Motherboard</h3>
                    <p>Pasang CPU, RAM, dan NVMe SSD di luar casing (di atas kotak mobo) untuk test boot awal.</p>
                    <h3>2. Manajemen Kabel (Cable Management)</h3>
                    <p>Gunakan velcro strap daripada zip ties agar mudah diubah. Alurkan kabel EPS CPU lewat belakang sebelum memasang motherboard.</p>
                    <h3>3. Airflow</h3>
                    <p>Pastikan intake (depan/bawah) lebih besar dari exhaust (belakang/atas) untuk menciptakan tekanan positif (positive pressure) agar debu tidak mudah masuk lewat celah sempit.</p>
                ',
            ],
            [
                'title' => 'Kebijakan Retur & Garansi (RMA)',
                'category' => 'Layanan Pelanggan',
                'content' => '
                    <p>Berikut adalah kebijakan standar garansi Yala Computer:</p>
                    <ul>
                        <li><strong>Dead on Arrival (DOA):</strong> Unit yang mati dalam 3x24 jam diganti baru (tukar unit).</li>
                        <li><strong>Garansi Distributor:</strong> Setelah 3 hari, unit akan kami bantu klaim ke distributor resmi. Estimasi waktu 14-45 hari kerja.</li>
                        <li><strong>Human Error:</strong> Garansi batal jika kerusakan disebabkan oleh cairan, benturan, atau kelistrikan rumah yang tidak stabil.</li>
                    </ul>
                ',
            ],
            [
                'title' => 'Cara Mengatasi Printer Error 5200 (Canon)',
                'category' => 'Troubleshooting',
                'content' => '
                    <p>Error 5200 biasanya terkait overheating pada Cartridge.</p>
                    <ol>
                        <li>Matikan printer.</li>
                        <li>Tekan dan tahan tombol Resume.</li>
                        <li>Tekan tombol Power (jangan lepas Resume).</li>
                        <li>Lepas tombol Resume, tekan 5 kali, lalu lepas tombol Power.</li>
                        <li>Printer akan masuk ke Service Mode. Lakukan cleaning via software.</li>
                    </ol>
                ',
            ],
            [
                'title' => 'Etika Chat WhatsApp dengan Pelanggan',
                'category' => 'SOP Operasional',
                'content' => '
                    <p>Standar komunikasi Yala Computer:</p>
                    <ul>
                        <li>Selalu awali dengan salam (Selamat Pagi/Siang/Sore).</li>
                        <li>Gunakan bahasa yang sopan namun tidak kaku.</li>
                        <li>Hindari singkatan alay (yg, gk, bkn).</li>
                        <li>Jika pelanggan marah, jangan terpancing emosi. Gunakan teknik "Maaf, Empati, Solusi".</li>
                    </ul>
                ',
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
