<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Pengaturan Sistem - Yala Computer')]
class Index extends Component
{
    use WithFileUploads;

    // General
    public $store_name;
    public $logo;
    public $whatsapp_number;
    public $address;
    
    // Storefront
    public $hero_title;
    public $hero_subtitle;
    public $footer_description;
    public $social_facebook;
    public $social_instagram;
    public $social_twitter;

    // Features & Modules
    public $feature_flash_sale;
    public $feature_service_tracking;
    public $store_announcement_text;
    public $store_announcement_active;

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

        $this->feature_flash_sale = (bool) Setting::get('feature_flash_sale', true);
        $this->feature_service_tracking = (bool) Setting::get('feature_service_tracking', true);
        $this->store_announcement_text = Setting::get('store_announcement_text', '');
        $this->store_announcement_active = (bool) Setting::get('store_announcement_active', false);

        $this->admin_title = Setting::get('admin_title', 'YALA SYSTEM');
        $this->bank_account = Setting::get('bank_account', 'BCA 1234567890 a.n Yala Computer');
        
        $this->maintenance_mode = (bool) Setting::get('maintenance_mode', false);
    }

    public function save()
    {
        if ($this->logo) {
            $path = $this->logo->store('logo', 'public');
            Setting::set('store_logo', $path);
        }

        Setting::set('store_name', $this->store_name);
        Setting::set('whatsapp_number', $this->whatsapp_number);
        Setting::set('address', $this->address);
        
        Setting::set('hero_title', $this->hero_title);
        Setting::set('hero_subtitle', $this->hero_subtitle);
        Setting::set('footer_description', $this->footer_description);
        
        Setting::set('social_facebook', $this->social_facebook);
        Setting::set('social_instagram', $this->social_instagram);
        Setting::set('social_twitter', $this->social_twitter);

        Setting::set('feature_flash_sale', $this->feature_flash_sale);
        Setting::set('feature_service_tracking', $this->feature_service_tracking);
        Setting::set('store_announcement_text', $this->store_announcement_text);
        Setting::set('store_announcement_active', $this->store_announcement_active);

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
