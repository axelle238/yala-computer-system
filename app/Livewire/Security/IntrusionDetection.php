<?php

namespace App\Livewire\Security;

use App\Models\ActivityLog;
use App\Models\Setting;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Sistem Deteksi & Pencegahan Intrusi (IDPS)')]
class IntrusionDetection extends Component
{
    use WithPagination;

    public $activeTab = 'threats'; // threats, analysis

    public $analysisResult = null;

    public function blockIp($ip)
    {
        if (! $ip) {
            return;
        }

        $blocked = json_decode(Setting::get('firewall_blocked_ips', '[]'), true) ?? [];
        if (! collect($blocked)->contains('ip', $ip)) {
            $blocked[] = [
                'ip' => $ip,
                'note' => 'Blocked via IDS Response',
                'added_at' => now()->toDateTimeString(),
                'added_by' => auth()->user()->name,
            ];
            Setting::set('firewall_blocked_ips', json_encode($blocked));

            // Log response action
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'firewall_block',
                'description' => "Memblokir IP $ip sebagai respons ancaman.",
                'ip_address' => request()->ip(),
            ]);

            $this->dispatch('notify', message: "IP $ip berhasil diblokir secara permanen.", type: 'success');
        } else {
            $this->dispatch('notify', message: "IP $ip sudah ada dalam daftar blokir.", type: 'info');
        }
    }

    public function killSession($userId)
    {
        if (! $userId) {
            return;
        }

        // In real app, this would invalidate session tokens.
        // Here we just log it and maybe force logout flag on user model if exists.
        // For simplicity in this mock environment:

        $user = User::find($userId);
        if ($user) {
            // $user->update(['remember_token' => null]); // Simple session invalidation attempt

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'session_kill',
                'description' => "Menghentikan paksa sesi user: {$user->name}",
                'ip_address' => request()->ip(),
            ]);

            $this->dispatch('notify', message: "Sesi pengguna {$user->name} telah diputus.", type: 'warning');
        }
    }

    public function analyzeThreat($ip)
    {
        // Mock Deep Analysis
        $logCount = ActivityLog::where('ip_address', $ip)->count();
        $failedCount = ActivityLog::where('ip_address', $ip)->where('action', 'like', '%fail%')->count();

        $score = min(100, ($failedCount * 10) + ($logCount * 1));
        $verdict = $score > 50 ? 'Malicious Actor' : 'Suspicious User';

        $this->analysisResult = [
            'ip' => $ip,
            'score' => $score,
            'verdict' => $verdict,
            'details' => [
                'Total Events' => $logCount,
                'Failed Attempts' => $failedCount,
                'Known Botnet' => rand(0, 1) ? 'Yes' : 'No', // Mock
                'Geo Location' => 'Unknown Proxy', // Mock
            ],
        ];

        $this->dispatch('open-analysis-modal');
    }

    public function render()
    {
        $threats = ActivityLog::where(function ($q) {
            $q->where('action', 'like', '%fail%')
                ->orWhere('action', 'like', '%error%')
                ->orWhere('action', 'like', '%denied%')
                ->orWhere('action', 'like', '%block%')
                ->orWhere('description', 'like', '%unauthorized%');
        })
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('livewire.security.intrusion-detection', [
            'threats' => $threats,
        ]);
    }
}
