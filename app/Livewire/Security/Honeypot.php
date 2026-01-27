<?php

namespace App\Livewire\Security;

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Honeypot & Deception System')]
class Honeypot extends Component
{
    public $traps = [];

    public $newTrapPath;

    public $newTrapType = 'login'; // login, api, file

    public function mount()
    {
        $this->traps = json_decode(Setting::get('security_honeypots', '[]'), true) ?? [];
    }

    public function addTrap()
    {
        $this->validate([
            'newTrapPath' => 'required|string|min:3',
            'newTrapType' => 'required|in:login,api,file',
        ]);

        $this->traps[] = [
            'path' => '/'.ltrim($this->newTrapPath, '/'),
            'type' => $this->newTrapType,
            'hits' => 0,
            'created_at' => now()->toDateTimeString(),
            'status' => 'active',
        ];

        Setting::set('security_honeypots', json_encode($this->traps));
        $this->reset(['newTrapPath', 'newTrapType']);
        $this->dispatch('notify', message: 'Jebakan (Honeypot) berhasil dipasang.', type: 'success');
    }

    public function deleteTrap($index)
    {
        unset($this->traps[$index]);
        $this->traps = array_values($this->traps);
        Setting::set('security_honeypots', json_encode($this->traps));
        $this->dispatch('notify', message: 'Honeypot dihapus.', type: 'info');
    }

    public function render()
    {
        return view('livewire.security.honeypot');
    }
}
