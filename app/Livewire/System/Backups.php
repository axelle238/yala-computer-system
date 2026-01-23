<?php

namespace App\Livewire\System;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Backup Manager - Yala Computer')]
class Backups extends Component
{
    public function render()
    {
        // List files in storage/app/backups
        $files = collect(Storage::files('backups'))
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'size' => Storage::size($file),
                    'last_modified' => Storage::lastModified($file),
                    'path' => $file
                ];
            })
            ->sortByDesc('last_modified');

        return view('livewire.system.backups', [
            'backups' => $files
        ]);
    }

    public function createBackup()
    {
        // Simple Database Dump Logic
        // Note: This relies on mysqldump being in PATH. If not, it fails silently or with error.
        
        $filename = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbHost = env('DB_HOST');

        // Command for Windows (XAMPP usually has mysqldump) or Linux
        // Warning: Using password in command line is not secure for production logs, but okay for internal tool MVP.
        $command = "mysqldump --user={$dbUser} --password={$dbPass} --host={$dbHost} {$dbName} > \"{$path}\"" ;
        
try {
            exec($command, $output, $returnVar);
            
            if ($returnVar === 0 && file_exists($path) && filesize($path) > 0) {
                $this->dispatch('notify', message: 'Backup database berhasil dibuat!', type: 'success');
            } else {
                $this->dispatch('notify', message: 'Gagal membuat backup. Pastikan mysqldump terinstall.', type: 'error');
            }
        } catch (
Exception $e) {
            $this->dispatch('notify', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function download($path)
    {
        return Storage::download($path);
    }

    public function delete($path)
    {
        Storage::delete($path);
        $this->dispatch('notify', message: 'File backup dihapus.', type: 'success');
    }
}
