<?php

namespace App\Livewire\System;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('System Backups')]
class Backups extends Component
{
    public $backups = [];

    public function mount()
    {
        $this->refreshBackups();
    }

    public function refreshBackups()
    {
        // Mocking backup list logic as we might not have 'spatie/laravel-backup' installed
        // In real app: Storage::disk('local')->files('YalaComputer');
        $this->backups = [
            ['name' => 'backup-2025-01-25-00-00-01.zip', 'size' => '12.5 MB', 'date' => now()->subHours(5)],
            ['name' => 'backup-2025-01-24-00-00-01.zip', 'size' => '12.4 MB', 'date' => now()->subDay()],
            ['name' => 'backup-2025-01-23-00-00-01.zip', 'size' => '12.1 MB', 'date' => now()->subDays(2)],
        ];
    }

    public function createBackup()
    {
        // Artisan::call('backup:run'); // Real command
        sleep(2); // Simulate process
        $this->dispatch('notify', message: 'Backup berhasil dibuat (Simulasi).', type: 'success');

        array_unshift($this->backups, [
            'name' => 'backup-'.date('Y-m-d-H-i-s').'.zip',
            'size' => '12.6 MB',
            'date' => now(),
        ]);
    }

    public function download($name)
    {
        $this->dispatch('notify', message: 'Mengunduh '.$name.'...', type: 'info');
    }

    public function delete($index)
    {
        unset($this->backups[$index]);
        $this->backups = array_values($this->backups);
        $this->dispatch('notify', message: 'Backup dihapus.', type: 'success');
    }

    public function render()
    {
        return view('livewire.system.backups');
    }
}
