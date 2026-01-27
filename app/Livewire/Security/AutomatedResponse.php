<?php

namespace App\Livewire\Security;

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Respon Ancaman Otomatis (ATM)')]
class AutomatedResponse extends Component
{
    public $rules = [];

    public function mount()
    {
        $this->rules = [
            'brute_force' => (bool) Setting::get('security_atm_brute_force', true),
            'sqli' => (bool) Setting::get('security_atm_sqli', true),
            'xss' => (bool) Setting::get('security_atm_xss', true),
            'bad_bot' => (bool) Setting::get('security_atm_bad_bot', false),
            'geo_block' => (bool) Setting::get('security_atm_geo_block', false),
            'storefront_login' => (bool) Setting::get('security_waf_storefront_login', true),
            'storefront_checkout' => (bool) Setting::get('security_waf_storefront_checkout', true),
        ];
    }

    public function toggleRule($rule)
    {
        $this->rules[$rule] = ! $this->rules[$rule];

        $key = str_starts_with($rule, 'storefront') ? "security_waf_{$rule}" : "security_atm_{$rule}";
        Setting::set($key, $this->rules[$rule]);

        $status = $this->rules[$rule] ? 'diaktifkan' : 'dinonaktifkan';
        $this->dispatch('notify', message: "Proteksi $rule berhasil $status.", type: 'success');
    }

    public function render()
    {
        return view('livewire.security.automated-response');
    }
}
