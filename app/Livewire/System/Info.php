<?php

namespace App\Livewire\System;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Informasi Sistem')]
class Info extends Component
{
    public function render()
    {
        $dbVersion = 'Unknown';
        $dbSize = 'Unknown';

        try {
            $pdo = DB::connection()->getPdo();
            $dbVersion = $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);

            // Estimasi Ukuran DB (MySQL specific)
            if (config('database.default') === 'mysql') {
                $dbName = config('database.connections.mysql.database');
                $result = DB::select('SELECT sum(data_length + index_length) / 1024 / 1024 as size FROM information_schema.TABLES WHERE table_schema = ?', [$dbName]);
                $dbSize = number_format($result[0]->size ?? 0, 2).' MB';
            }
        } catch (\Exception $e) {
            $dbVersion = 'Connection Error';
        }

        // Disk Usage
        $diskTotal = disk_total_space(base_path());
        $diskFree = disk_free_space(base_path());
        $diskUsed = $diskTotal - $diskFree;
        $diskUsagePercent = ($diskUsed / $diskTotal) * 100;

        // Security Stats
        $loginAttempts = ActivityLog::where('action', 'login')->where('created_at', '>=', now()->subDay())->count();
        $distinctIps = ActivityLog::where('created_at', '>=', now()->subDay())->distinct('ip_address')->count('ip_address');

        return view('livewire.system.info', [
            'phpVersion' => phpversion(),
            'laravelVersion' => app()->version(),
            'serverSoftware' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'databaseConnection' => config('database.default'),
            'databaseVersion' => $dbVersion,
            'databaseSize' => $dbSize,
            'extensions' => get_loaded_extensions(),
            'os' => php_uname(),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'memoryLimit' => ini_get('memory_limit'),
            'uploadMaxFilesize' => ini_get('upload_max_filesize'),
            'postMaxSize' => ini_get('post_max_size'),
            'maxExecutionTime' => ini_get('max_execution_time'),
            'clientIp' => request()->ip(),
            'userAgent' => request()->userAgent(),
            'diskTotal' => $this->formatBytes($diskTotal),
            'diskFree' => $this->formatBytes($diskFree),
            'diskUsed' => $this->formatBytes($diskUsed),
            'diskUsagePercent' => $diskUsagePercent,
            'environment' => app()->environment(),
            'debugMode' => config('app.debug'),
            'maintenanceMode' => app()->isDownForMaintenance(),
            'queueDriver' => config('queue.default'),
            'cacheDriver' => config('cache.default'),
            'sessionDriver' => config('session.driver'),
            'loginAttempts' => $loginAttempts,
            'distinctIps' => $distinctIps,
        ]);
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }
}
