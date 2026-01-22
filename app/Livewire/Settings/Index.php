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
    // General
    public $store_name;
    public $whatsapp_number;
    public $address;
    
    // Storefront
    public $hero_title;
    public $hero_subtitle;
    public $footer_description;
    public $social_facebook;
    public $social_instagram;
    public $social_twitter;

    // Admin & System
    public $admin_title;
    public $bank_account;
    public $maintenance_mode = false;

    public function mount()
    {
        $this->store_name = Setting::get('store_name', 'Yala Computer');
        $this->whatsapp_number = Setting::get('whatsapp_number', '6281234567890');
        $this->address = Setting::get('address', 'Jl. Teknologi No. 1');
        
        $this->hero_title = Setting::get('hero_title', 'FUTURE TECH');
        $this->hero_subtitle = Setting::get('hero_subtitle', 'Platform belanja hardware komputer tercanggih.');
        $this->footer_description = Setting::get('footer_description', 'Menyediakan hardware PC High-End dan layanan rakitan profesional.');
        
        $this->social_facebook = Setting::get('social_facebook', '#');
        $this->social_instagram = Setting::get('social_instagram', '#');
        $this->social_twitter = Setting::get('social_twitter', '#');

        $this->admin_title = Setting::get('admin_title', 'YALA SYSTEM');
        $this->bank_account = Setting::get('bank_account', 'BCA 1234567890 a.n Yala Computer');
        
        $this->maintenance_mode = (bool) Setting::get('maintenance_mode', false);
    }

    public function save()
    {
        Setting::set('store_name', $this->store_name);
        Setting::set('whatsapp_number', $this->whatsapp_number);
        Setting::set('address', $this->address);
        
        Setting::set('hero_title', $this->hero_title);
        Setting::set('hero_subtitle', $this->hero_subtitle);
        Setting::set('footer_description', $this->footer_description);
        
        Setting::set('social_facebook', $this->social_facebook);
        Setting::set('social_instagram', $this->social_instagram);
        Setting::set('social_twitter', $this->social_twitter);

        Setting::set('admin_title', $this->admin_title);
        Setting::set('bank_account', $this->bank_account);
        
        Setting::set('maintenance_mode', $this->maintenance_mode);

        $this->dispatch('notify', message: 'Pengaturan disimpan!', type: 'success');
    }

    public function render()
    {
        return view('livewire.settings.index');
    }
}
