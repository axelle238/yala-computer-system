<?php

namespace App\Livewire\Security;

use App\Models\ActivityLog;
use App\Models\Setting;
use Livewire\Component;

class Widget extends Component
{
    public $status = 'safe'; // safe, warning, critical

    public function render()
    {
        $lockdown = Setting::get('security_lockdown_mode');

        if ($lockdown) {
            $this->status = 'critical';
        } else {
            // Check recent threats
            $recentThreats = ActivityLog::where('action', 'threat_detected')
                ->where('created_at', '>=', now()->subMinutes(5))
                ->count();

            if ($recentThreats > 10) {
                $this->status = 'critical';
            } elseif ($recentThreats > 0) {
                $this->status = 'warning';
            } else {
                $this->status = 'safe';
            }
        }

        return view('livewire.security.widget');
    }
}
