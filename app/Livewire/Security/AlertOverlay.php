<?php

namespace App\Livewire\Security;

use App\Models\Setting;
use Livewire\Component;

class AlertOverlay extends Component
{
    public $lockdownMode = false;

    public $activeThreats = false; // Mock for now, or check logs

    public function render()
    {
        // Check only every poll (default 2s in view)
        $this->lockdownMode = (bool) Setting::get('security_lockdown_mode', false);
        // Simulate checking for high severity unresolved threats
        // $this->activeThreats = ActivityLog::where('action', 'threat_detected')->where('created_at', '>', now()->subMinutes(5))->exists();

        return view('livewire.security.alert-overlay');
    }
}
