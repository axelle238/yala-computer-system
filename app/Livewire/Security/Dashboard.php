<?php

namespace App\Livewire\Security;

use App\Models\ActivityLog;
use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Pusat Komando Keamanan Siber (SOC)')]
class Dashboard extends Component
{
    public $lockdownMode = false;

    public $autoBanEnabled = false;

    public function mount()
    {
        $this->lockdownMode = (bool) Setting::get('security_lockdown_mode', false);
        $this->autoBanEnabled = (bool) Setting::get('security_auto_ban', true);
    }

    public function toggleLockdown()
    {
        $this->lockdownMode = ! $this->lockdownMode;
        Setting::set('security_lockdown_mode', $this->lockdownMode);

        $msg = $this->lockdownMode
            ? 'MODE DARURAT DIAKTIFKAN! Sistem sekarang dalam perlindungan maksimal.'
            : 'Mode Darurat dinonaktifkan. Sistem kembali normal.';

        $type = $this->lockdownMode ? 'error' : 'success';

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'security_lockdown_toggle',
            'description' => $msg,
            'ip_address' => request()->ip(),
        ]);

        $this->dispatch('notify', message: $msg, type: $type);
    }

    public function toggleAutoBan()
    {
        $this->autoBanEnabled = ! $this->autoBanEnabled;
        Setting::set('security_auto_ban', $this->autoBanEnabled);
        $this->dispatch('notify', message: 'Pengaturan Auto-Ban diperbarui.', type: 'info');
    }

    public function resolveThreat($logId)
    {
        $log = ActivityLog::find($logId);
        if ($log) {
            // Mock resolution logic
            $log->update(['description' => $log->description.' [RESOLVED]']);

            // If it has IP, block it
            if ($log->ip_address) {
                $blocked = json_decode(Setting::get('firewall_blocked_ips', '[]'), true) ?? [];
                if (! collect($blocked)->contains('ip', $log->ip_address)) {
                    $blocked[] = [
                        'ip' => $log->ip_address,
                        'note' => 'Auto-resolved from Dashboard',
                        'added_at' => now()->toDateTimeString(),
                        'added_by' => 'System (Automated)',
                    ];
                    Setting::set('firewall_blocked_ips', json_encode($blocked));
                }
            }

            $this->dispatch('notify', message: 'Ancaman telah ditangani. IP Address diblokir.', type: 'success');
        }
    }

    public function render()
    {
        // 1. Core Metrics (Real Data)
        $totalEvents = ActivityLog::count();
        $recentLogins = ActivityLog::where('action', 'login')->where('created_at', '>=', now()->subDay())->count();
        $failedLogins = ActivityLog::where('action', 'login_failed')->where('created_at', '>=', now()->subDay())->count();
        $distinctIps = ActivityLog::where('created_at', '>=', now()->subDay())->distinct('ip_address')->count('ip_address');

        // 2. Real-time Attack Surface Analysis
        $threatLevel = 'Low';
        $threatScore = 15; // Base score
        $activeThreats = 0;
        $systemIntegrity = 100;

        // Dynamic Score Calculation
        if ($this->lockdownMode) {
            $threatLevel = 'Critical';
            $threatScore = 95;
            $activeThreats += 10;
            $systemIntegrity = 50;
        } elseif ($failedLogins > 50) {
            $threatLevel = 'High';
            $threatScore += 60;
            $activeThreats += 5;
            $systemIntegrity -= 20;
        } elseif ($failedLogins > 10) {
            $threatLevel = 'Medium';
            $threatScore += 30;
            $activeThreats += 2;
            $systemIntegrity -= 5;
        }

        // 3. Traffic Analysis (Mock Data with Randomness for "Real-time" feel)
        $trafficData = [
            'labels' => collect(range(0, 11))->map(fn ($i) => now()->subHours(11 - $i)->format('H:i'))->toArray(),
            'values' => collect(range(0, 11))->map(fn () => rand(50, 200) + ($this->lockdownMode ? 500 : 0))->toArray(),
            'anomalies' => collect(range(0, 11))->map(fn () => rand(0, 10) > 8 ? rand(20, 50) : 0)->toArray(),
        ];

        // 4. Geo Distribution (Mock)
        $geoDistribution = [
            ['country' => 'Indonesia', 'count' => 850, 'status' => 'Safe', 'flag' => '🇮🇩'],
            ['country' => 'United States', 'count' => 120, 'status' => 'Neutral', 'flag' => '🇺🇸'],
            ['country' => 'China', 'count' => 45, 'status' => 'Suspicious', 'flag' => '🇨🇳'],
            ['country' => 'Russia', 'count' => 12, 'status' => 'High Risk', 'flag' => '🇷🇺'],
            ['country' => 'Unknown Proxy', 'count' => 8, 'status' => 'Critical', 'flag' => '🏴‍☠️'],
        ];

        // 5. Recent Security Events
        $securityEvents = ActivityLog::whereIn('action', ['login', 'login_failed', 'password_change', 'role_change', 'settings_update', 'firewall_block', 'threat_detected', 'security_lockdown_toggle'])
            ->with('user')
            ->latest()
            ->take(8)
            ->get();

        // 6. Attack Map Data (Mock)
        $attackMapData = [];
        for ($i = 0; $i < 5; $i++) {
            $attackMapData[] = [
                'src_lat' => rand(-50, 50),
                'src_lon' => rand(-150, 150),
                'dst_lat' => -6.2088, // Jakarta
                'dst_lon' => 106.8456,
                'type' => ['DDoS', 'SQLi', 'Brute Force'][rand(0, 2)],
                'severity' => rand(1, 10) > 7 ? 'Critical' : 'Warning',
            ];
        }

        return view('livewire.security.dashboard', [
            'totalEvents' => $totalEvents,
            'recentLogins' => $recentLogins,
            'failedLogins' => $failedLogins,
            'distinctIps' => $distinctIps,
            'threatLevel' => $threatLevel,
            'threatScore' => min(100, $threatScore),
            'activeThreats' => $activeThreats,
            'systemIntegrity' => max(0, $systemIntegrity),
            'securityEvents' => $securityEvents,
            'trafficData' => $trafficData,
            'geoDistribution' => $geoDistribution,
            'attackMapData' => $attackMapData,
        ]);
    }
}
