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
        ], [
            'formulir.store_name.required' => 'Nama toko wajib diisi.',
            'formulir.store_email.required' => 'Email toko wajib diisi.',
            'formulir.store_email.email' => 'Format email tidak valid.',
            'formulir.store_phone.required' => 'Nomor telepon wajib diisi.',
            'logoBaru.image' => 'File logo harus berupa gambar.',
            'logoBaru.max' => 'Ukuran logo maksimal 1MB.',
            'faviconBaru.image' => 'File favicon harus berupa gambar.',
            'faviconBaru.max' => 'Ukuran favicon maksimal 512KB.',
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

    public function render()
    {
        return view('livewire.settings.index');
    }
}
