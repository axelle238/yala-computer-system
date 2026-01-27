<?php

namespace App\Livewire\Security;

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Firewall & Access Control List')]
class Firewall extends Component
{
    public $blockedIps = [];

    public $whitelistedIps = [];

    public $blockedCountries = [];

    public $blockedUserAgents = [];

    public $newIp;

    public $ipNote;

    public $newCountry; // ISO Code

    public $newUa;

    public $activeTab = 'ip_rules'; // ip_rules, geo_block, ua_block, rate_limit

    // Rate Limit Settings
    public $rateLimitEnabled = false;

    public $maxRequests = 60;

    public $decayMinutes = 1;

    public function mount()
    {
        $this->loadRules();
    }

    public function loadRules()
    {
        $this->blockedIps = json_decode(Setting::get('firewall_blocked_ips', '[]'), true) ?? [];
        $this->whitelistedIps = json_decode(Setting::get('firewall_whitelisted_ips', '[]'), true) ?? [];
        $this->blockedCountries = json_decode(Setting::get('firewall_blocked_countries', '[]'), true) ?? [];
        $this->blockedUserAgents = json_decode(Setting::get('firewall_blocked_ua', '[]'), true) ?? [];

        $this->rateLimitEnabled = (bool) Setting::get('firewall_rate_limit_enabled', false);
        $this->maxRequests = (int) Setting::get('firewall_rate_limit_max', 60);
        $this->decayMinutes = (int) Setting::get('firewall_rate_limit_decay', 1);
    }

    public function addIp($type) // type: blocked / whitelist
    {
        $this->validate([
            'newIp' => 'required|ip',
            'ipNote' => 'nullable|string|max:255',
        ]);

        $entry = [
            'ip' => $this->newIp,
            'note' => $this->ipNote,
            'added_at' => now()->toDateTimeString(),
            'added_by' => auth()->user()->name,
        ];

        if ($type == 'blocked') {
            $this->blockedIps[] = $entry;
            Setting::set('firewall_blocked_ips', json_encode($this->blockedIps));
            $this->dispatch('notify', message: 'IP berhasil diblokir.', type: 'error');
        } else {
            $this->whitelistedIps[] = $entry;
            Setting::set('firewall_whitelisted_ips', json_encode($this->whitelistedIps));
            $this->dispatch('notify', message: 'IP berhasil di-whitelist.', type: 'success');
        }

        $this->reset(['newIp', 'ipNote']);
    }

    public function removeIp($type, $index)
    {
        if ($type == 'blocked') {
            unset($this->blockedIps[$index]);
            $this->blockedIps = array_values($this->blockedIps);
            Setting::set('firewall_blocked_ips', json_encode($this->blockedIps));
        } else {
            unset($this->whitelistedIps[$index]);
            $this->whitelistedIps = array_values($this->whitelistedIps);
            Setting::set('firewall_whitelisted_ips', json_encode($this->whitelistedIps));
        }
        $this->dispatch('notify', message: 'Aturan IP dihapus.', type: 'info');
    }

    public function addCountry()
    {
        $this->validate(['newCountry' => 'required|string|size:2']);

        $this->blockedCountries[] = strtoupper($this->newCountry);
        Setting::set('firewall_blocked_countries', json_encode($this->blockedCountries));

        $this->reset('newCountry');
        $this->dispatch('notify', message: 'Negara diblokir.', type: 'error');
    }

    public function removeCountry($index)
    {
        unset($this->blockedCountries[$index]);
        $this->blockedCountries = array_values($this->blockedCountries);
        Setting::set('firewall_blocked_countries', json_encode($this->blockedCountries));
        $this->dispatch('notify', message: 'Blokir negara dihapus.', type: 'info');
    }

    public function addUa()
    {
        $this->validate(['newUa' => 'required|string|min:3']);

        $this->blockedUserAgents[] = $this->newUa;
        Setting::set('firewall_blocked_ua', json_encode($this->blockedUserAgents));

        $this->reset('newUa');
        $this->dispatch('notify', message: 'User Agent diblokir.', type: 'error');
    }

    public function removeUa($index)
    {
        unset($this->blockedUserAgents[$index]);
        $this->blockedUserAgents = array_values($this->blockedUserAgents);
        Setting::set('firewall_blocked_ua', json_encode($this->blockedUserAgents));
        $this->dispatch('notify', message: 'Blokir UA dihapus.', type: 'info');
    }

    public function saveRateLimit()
    {
        Setting::set('firewall_rate_limit_enabled', $this->rateLimitEnabled);
        Setting::set('firewall_rate_limit_max', $this->maxRequests);
        Setting::set('firewall_rate_limit_decay', $this->decayMinutes);

        $this->dispatch('notify', message: 'Pengaturan Rate Limit disimpan.', type: 'success');
    }

    public function render()
    {
        return view('livewire.security.firewall');
    }
}
