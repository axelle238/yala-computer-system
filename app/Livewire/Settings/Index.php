<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Pusat Pengaturan Sistem - Yala Computer')]
class Index extends Component
{
    use WithFileUploads;

    public $grupPengaturan = [];

    public $formulir = [];

    public $logoBaru;

    public $faviconBaru;

    // Tab Aktif
    public $tabAktif = 'umum';

    public function mount()
    {
        $this->muatPengaturan();
    }

    public function gantiTab($tab)
    {
        $this->tabAktif = $tab;
    }

    public function muatPengaturan()
    {
        // Daftar semua kunci pengaturan yang didukung
        $kunciWajib = [
            // Umum
            'store_name', 'store_address', 'store_phone', 'store_email',
            'store_announcement_active', 'store_announcement_text',
            // Keuangan & Midtrans
            'midtrans_server_key', 'midtrans_client_key', 'midtrans_merchant_id', 'midtrans_is_production',
            'tax_rate', 'service_charge',
            // Sistem & Integrasi
            'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption',
            'whatsapp_gateway_url', 'printer_ip_address',
            // SEO & Media Sosial
            'seo_meta_description', 'seo_meta_keywords',
            'social_facebook', 'social_instagram', 'social_tiktok',
            // Template Notifikasi (WhatsApp)
            'wa_template_order_success', 'wa_template_payment_reminder',
        ];

        // Pastikan setting ada di database, jika tidak buat default kosong
        foreach ($kunciWajib as $kunci) {
            if (! Setting::where('key', $kunci)->exists()) {
                Setting::create(['key' => $kunci, 'value' => null]);
            }
        }

        $semuaPengaturan = Setting::all();

        foreach ($semuaPengaturan as $p) {
            $this->formulir[$p->key] = $p->value;
        }

        // Handling boolean checkbox
        $this->formulir['store_announcement_active'] = (bool) ($this->formulir['store_announcement_active'] ?? false);
        $this->formulir['midtrans_is_production'] = (bool) ($this->formulir['midtrans_is_production'] ?? false);
    }

    public function simpan()
    {
        $this->validate([
            'formulir.store_name' => 'required|string|max:255',
            'formulir.store_email' => 'required|email',
            'formulir.store_phone' => 'required|string',
            'logoBaru' => 'nullable|image|max:1024',
            'faviconBaru' => 'nullable|image|max:512',
            'formulir.social_facebook' => 'nullable|url',
            'formulir.social_instagram' => 'nullable|url',
            'formulir.social_tiktok' => 'nullable|url',
        ], [
            'formulir.store_name.required' => 'Nama toko wajib diisi.',
            'formulir.store_email.required' => 'Email toko wajib diisi.',
            'formulir.store_email.email' => 'Format email tidak valid.',
            'formulir.store_phone.required' => 'Nomor telepon wajib diisi.',
            'logoBaru.image' => 'File logo harus berupa gambar.',
            'logoBaru.max' => 'Ukuran logo maksimal 1MB.',
            'faviconBaru.image' => 'File favicon harus berupa gambar.',
            'faviconBaru.max' => 'Ukuran favicon maksimal 512KB.',
            'formulir.social_facebook.url' => 'Format URL Facebook tidak valid.',
            'formulir.social_instagram.url' => 'Format URL Instagram tidak valid.',
            'formulir.social_tiktok.url' => 'Format URL TikTok tidak valid.',
        ]);

        // Upload Logo
        if ($this->logoBaru) {
            $path = $this->logoBaru->store('settings', 'public');
            Setting::set('store_logo', $path);
        }

        // Upload Favicon
        if ($this->faviconBaru) {
            $path = $this->faviconBaru->store('settings', 'public');
            Setting::set('store_favicon', $path);
        }

        // Simpan Form
        foreach ($this->formulir as $kunci => $nilai) {
            Setting::set($kunci, $nilai);
        }

        $this->logoBaru = null;
        $this->faviconBaru = null;

        $this->dispatch('notify', message: 'Pengaturan sistem berhasil diperbarui.', type: 'success');
    }

    public function resetKeDefault()
    {
        $this->formulir = [
            'store_name' => 'Yala Computer',
            'store_address' => '',
            'store_phone' => '',
            'store_email' => '',
            'store_announcement_active' => false,
            'store_announcement_text' => '',
            'midtrans_is_production' => false,
            'tax_rate' => 11,
            'service_charge' => 0,
            'seo_meta_description' => 'Toko komputer terlengkap dan jasa rakit PC terpercaya.',
            'seo_meta_keywords' => 'komputer, laptop, rakit pc, servis komputer',
            'social_facebook' => '',
            'social_instagram' => '',
            'social_tiktok' => '',
            'wa_template_order_success' => 'Halo {name}, pesanan #{order_id} Anda telah berhasil dibuat. Silakan lakukan pembayaran.',
            'wa_template_payment_reminder' => 'Halo {name}, jangan lupa selesaikan pembayaran untuk pesanan #{order_id} Anda.',
        ];

        $this->dispatch('notify', message: 'Formulir telah direset ke nilai default (Belum Disimpan).', type: 'info');
    }

    public function render()
    {
        return view('livewire.settings.index');
    }
}
