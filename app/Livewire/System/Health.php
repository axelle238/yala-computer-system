<?php

namespace App\Livewire\System;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Kesehatan Sistem & Log - Yala Computer')]
class Health extends Component
{
    public $activeTab = 'health'; // health, logs, cache
    
    // Log Viewer Data
    public $logContent = [];
    public $logFile = 'laravel.log';

    public function mount()
    {
        $this->readLogs();
    }

    // --- Actions ---

    public function runCommand($command)
    {
        try {
            Artisan::call($command);
            $output = Artisan::output();
            $this->dispatch('notify', message: "Perintah '$command' sukses! Output: " . substr($output, 0, 100), type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: "Gagal: " . $e->getMessage(), type: 'error');
        }
    }

    public function clearLogs()
    {
        $path = storage_path('logs/' . $this->logFile);
        if (File::exists($path)) {
            File::put($path, '');
            $this->readLogs();
            $this->dispatch('notify', message: 'Log berhasil dibersihkan.', type: 'success');
        }
    }

    public function readLogs()
    {
        $path = storage_path('logs/' . $this->logFile);
        if (!File::exists($path)) {
            $this->logContent = ['Log file not found.'];
            return;
        }

        // Read last 1000 lines for performance
        $content = File::get($path);
        
        // Split and reverse to show newest first
        $lines = explode("\n", $content);
        $lines = array_reverse(array_filter($lines));
        $this->logContent = array_slice($lines, 0, 200); // Take top 200
    }

    // --- Metrics ---

    public function getSystemInfoProperty()
    {
        return [
            'php' => phpversion(),
            'laravel' => app()->version(),
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'db_connection' => $this->checkDatabase(),
            'disk_free' => $this->formatBytes(disk_free_space(base_path())),
            'disk_total' => $this->formatBytes(disk_total_space(base_path())),
        ];
    }

    private function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            return 'Terhubung (' . DB::connection()->getDatabaseName() . ')';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    private function formatBytes($bytes, $precision = 2) 
    { 
        $units = ['B', 'KB', 'MB', 'GB', 'TB']; 
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
        $bytes /= pow(1024, $pow); 
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    }

    public function render()
    {
        return view('livewire.system.health');
    }
}