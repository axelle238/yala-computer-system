<?php

namespace App\Livewire\Settings;

use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Pusat Kendali Sistem - Yala Computer')]
class Index extends Component
{
    use WithFileUploads;

    // Data Formulir
    public $formulir = [];
    public $logoBaru;
    public $faviconBaru;

    // Navigasi Tab
    public $tabAktif = 'umum';

    /**
     * Inisialisasi komponen.
     */
    public function mount()
    {
        $this->muatDataPengaturan();
    }

    /**
     * Berpindah kategori pengaturan.
     */
    public function gantiKategori($namaTab)
    {
        $this->tabAktif = $namaTab;
    }

    /**
     * Mengambil data dari database dan sinkronisasi kunci wajib.
     */
    public function muatDataPengaturan()
    {
        $daftarKunciWajib = [
            'store_name', 'store_address', 'store_phone', 'store_email',
            'store_announcement_active', 'store_announcement_text',
            'midtrans_server_key', 'midtrans_client_key', 'midtrans_merchant_id', 'midtrans_is_production',
            'tax_rate', 'service_charge',
            'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption',
            'whatsapp_gateway_url', 'printer_ip_address',
            'seo_meta_description', 'seo_meta_keywords',
            'social_facebook', 'social_instagram', 'social_tiktok',
            'wa_template_order_success', 'wa_template_payment_reminder',
            'store_open_mon', 'store_close_mon',
            'store_open_tue', 'store_close_tue',
            'store_open_wed', 'store_close_wed',
            'store_open_thu', 'store_close_thu',
            'store_open_fri', 'store_close_fri',
            'store_open_sat', 'store_close_sat',
            'store_open_sun', 'store_close_sun',
        ];

        // Pastikan integritas database
        foreach ($daftarKunciWajib as $kunci) {
            if (! Setting::where('key', $kunci)->exists()) {
                Setting::create(['key' => $kunci, 'value' => null]);
            }
        }

        $semuaSetting = Setting::all();
        foreach ($semuaSetting as $s) {
            $this->formulir[$s->key] = $s->value;
        }

        // Konversi tipe data UI
        $this->formulir['store_announcement_active'] = (bool) ($this->formulir['store_announcement_active'] ?? false);
        $this->formulir['midtrans_is_production'] = (bool) ($this->formulir['midtrans_is_production'] ?? false);
    }

    /**
     * Menyimpan seluruh perubahan pengaturan dan mencatat audit log (Kompleks).
     */
    public function simpanPerubahan()
    {
        $this->validate([
            'formulir.store_name' => 'required|string|max:255',
            'formulir.store_email' => 'required|email',
            'formulir.store_phone' => 'required|string',
            'logoBaru' => 'nullable|image|max:1024',
            'faviconBaru' => 'nullable|image|max:512',
        ], [
            'formulir.store_name.required' => 'Nama identitas toko wajib diisi.',
            'formulir.store_email.email' => 'Format alamat surel tidak valid.',
        ]);

        $daftarPerubahan = [];

        // 1. Tangani Upload Media
        if ($this->logoBaru) {
            $jalur = $this->logoBaru->store('pengaturan', 'public');
            Setting::set('store_logo', $jalur);
            $daftarPerubahan[] = "Memperbarui logo toko";
        }

        if ($this->faviconBaru) {
            $jalur = $this->faviconBaru->store('pengaturan', 'public');
            Setting::set('store_favicon', $jalur);
            $daftarPerubahan[] = "Memperbarui favicon sistem";
        }

        // 2. Bandingkan dan Simpan Data Teks
        foreach ($this->formulir as $kunci => $nilaiBaru) {
            $nilaiLama = Setting::get($kunci);
            
            // Logika deteksi perubahan string/bool
            if (trim((string)$nilaiLama) !== trim((string)$nilaiBaru)) {
                Setting::set($kunci, $nilaiBaru);
                $daftarPerubahan[] = "Mengubah '{$kunci}'";
            }
        }

        // 3. Catat Audit Log Mendalam
        if (!empty($daftarPerubahan)) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity_type' => 'update_settings',
                'description' => "Melakukan pembaruan konfigurasi sistem pada tab: " . strtoupper($this->tabAktif),
                'properties' => [
                    'item_yang_berubah' => $daftarPerubahan,
                    'ip_address' => request()->ip()
                ],
            ]);
        }

        $this->logoBaru = null;
        $this->faviconBaru = null;

        $this->dispatch('notify', message: 'Seluruh konfigurasi sistem berhasil diperbarui dan dicatat.', type: 'success');
    }

    /**
     * Mengembalikan formulir ke setelan pabrik (Default).
     */
    public function resetKeSetelanAwal()
    {
        $this->formulir = [
            'store_name' => 'Yala Computer',
            'store_address' => 'Jl. Teknologi No. 1, Jakarta',
            'store_phone' => '021-1234567',
            'store_email' => 'halo@yalacomputer.com',
            'store_announcement_active' => false,
            'store_announcement_text' => 'Selamat datang di masa depan teknologi.',
            'midtrans_is_production' => false,
            'tax_rate' => 11,
            'service_charge' => 0,
            'seo_meta_description' => 'Toko komputer terlengkap dan rakit PC profesional.',
            'seo_meta_keywords' => 'komputer, laptop, rakit pc, gaming',
            'social_facebook' => '',
            'social_instagram' => '',
            'social_tiktok' => '',
            'wa_template_order_success' => 'Halo {{nama}}, pesanan Anda #{{order_id}} sedang diproses.',
            'wa_template_payment_reminder' => 'Halo {{nama}}, silakan selesaikan pembayaran untuk #{{order_id}}.',
            'store_open_mon' => '09:00', 'store_close_mon' => '17:00',
            'store_open_tue' => '09:00', 'store_close_tue' => '17:00',
            'store_open_wed' => '09:00', 'store_close_wed' => '17:00',
            'store_open_thu' => '09:00', 'store_close_thu' => '17:00',
            'store_open_fri' => '09:00', 'store_close_fri' => '17:00',
            'store_open_sat' => '09:00', 'store_close_sat' => '15:00',
            'store_open_sun' => 'Tutup', 'store_close_sun' => 'Tutup',
        ];

        $this->dispatch('notify', message: 'Setelan dikembalikan ke default. Klik simpan untuk menerapkan.', type: 'info');
    }

    public function render()
    {
        return view('livewire.settings.index');
    }
}