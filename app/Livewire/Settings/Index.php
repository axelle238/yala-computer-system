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
    public $settings;
    public $form = [];

    public function mount()
    {
        $this->settings = Setting::all();
        foreach ($this->settings as $setting) {
            $this->form[$setting->key] = $setting->value;
        }
    }

    public function save()
    {
        foreach ($this->form as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        session()->flash('success', 'Pengaturan sistem berhasil diperbarui!');
        $this->dispatch('settings-updated'); // Optional: jika ada komponen lain yang perlu refresh
    }

    public function render()
    {
        return view('livewire.settings.index');
    }
}
