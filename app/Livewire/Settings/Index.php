<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Pengaturan Sistem - Yala Computer')]
class Index extends Component
{
    public $store_name;
    public $whatsapp_number;
    public $address;
    public $maintenance_mode = false;

    public function mount()
    {
        $this->store_name = Setting::get('store_name', 'Yala Computer');
        $this->whatsapp_number = Setting::get('whatsapp_number', '6281234567890');
        $this->address = Setting::get('address', 'Jl. Teknologi No. 1');
        $this->maintenance_mode = (bool) Setting::get('maintenance_mode', false);
    }

    public function save()
    {
        Setting::set('store_name', $this->store_name);
        Setting::set('whatsapp_number', $this->whatsapp_number);
        Setting::set('address', $this->address);
        Setting::set('maintenance_mode', $this->maintenance_mode);

        $this->dispatch('notify', message: 'Pengaturan disimpan!', type: 'success');
    }

    public function render()
    {
        return view('livewire.settings.index');
    }
}
