<?php

namespace App\Livewire\Security;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Traffic Inspector (Deep Packet Inspection)')]
class TrafficInspector extends Component
{
    public $requests = [];

    public $isPaused = false;

    public function mount()
    {
        $this->generateMockTraffic();
    }

    public function loadMore()
    {
        if (! $this->isPaused) {
            $this->generateMockTraffic();
        }
    }

    public function togglePause()
    {
        $this->isPaused = ! $this->isPaused;
    }

    private function generateMockTraffic()
    {
        // Simulate incoming requests
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'HEAD', 'OPTIONS'];
        $paths = ['/login', '/admin/dashboard', '/api/products', '/cart', '/checkout', '/wp-login.php', '/.env'];
        $ips = ['192.168.1.1', '10.0.0.5', '172.16.0.1', '203.0.113.5', '198.51.100.2'];
        $agents = ['Mozilla/5.0', 'curl/7.68.0', 'Python-urllib/3.8', 'Googlebot/2.1'];

        $new = [];
        for ($i = 0; $i < 5; $i++) {
            $path = $paths[array_rand($paths)];
            $status = 200;
            if (str_contains($path, 'wp-') || str_contains($path, '.env')) {
                $status = 403;
            }

            $new[] = [
                'id' => uniqid(),
                'method' => $methods[array_rand($methods)],
                'path' => $path,
                'ip' => $ips[array_rand($ips)],
                'ua' => $agents[array_rand($agents)],
                'status' => $status,
                'time' => now()->format('H:i:s.u'),
                'size' => rand(100, 5000).'B',
            ];
        }

        $this->requests = array_merge($new, array_slice($this->requests, 0, 45));
    }

    public function render()
    {
        return view('livewire.security.traffic-inspector');
    }
}
