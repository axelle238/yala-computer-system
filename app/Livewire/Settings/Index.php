<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
#[Title('Pengaturan Toko & Sistem')]
class Index extends Component
{
    public $settingsGrouped = [];
    public $form = [];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $allSettings = Setting::all();
        
        // Grouping logic (bisa based on key prefix or manual mapping)
        $this->settingsGrouped = [
            'Toko' => $allSettings->whereIn('key', ['store_name', 'store_address', 'store_phone']),
            'Keuangan' => $allSettings->whereIn('key', ['tax_rate', 'receipt_footer']),
            'Perangkat' => $allSettings->whereIn('key', ['printer_ip']),
        ];

        foreach ($allSettings as $s) {
            $this->form[$s->key] = $s->value;
        }
    }

    public function save()
    {
        foreach ($this->form as $key => $value) {
            Setting::set($key, $value);
        }

        session()->flash('success', 'Konfigurasi berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.settings.index');
    }
}
