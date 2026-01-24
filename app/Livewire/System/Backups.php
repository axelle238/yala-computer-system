<?php

namespace App\Livewire\System;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Backup Database - Yala Computer')]
class Backups extends Component
{
    public $backups = [];

    public function mount()
    {
        $this->loadBackups();
    }

    public function loadBackups()
    {
        // Mocking backup list from storage
        // In production, use spatie/laravel-backup to get real list
        $files = Storage::allFiles('YalaComputer'); // Assuming default backup name
        $this->backups = collect($files)->map(function ($file) {
            return [
                'path' => $file,
                'name' => basename($file),
                'size' => Storage::size($file),
                'date' => Storage::lastModified($file),
            ];
        })->sortByDesc('date')->values()->all();
    }

    public function createBackup()
    {
        try {
            // Artisan::call('backup:run'); // Real command
            // Simulate for prototype
            $filename = 'YalaComputer/backup-' . date('Y-m-d-H-i-s') . '.zip';
            Storage::put($filename, 'Mock Backup Content'); 
            
            $this->loadBackups();
            $this->dispatch('notify', message: 'Backup berhasil dibuat.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Gagal membuat backup: ' . $e->getMessage(), type: 'error');
        }
    }

    public function download($path)
    {
        return Storage::download($path);
    }

    public function delete($path)
    {
        Storage::delete($path);
        $this->loadBackups();
        $this->dispatch('notify', message: 'Backup dihapus.');
    }

    public function render()
    {
        return view('livewire.system.backups');
    }
}
