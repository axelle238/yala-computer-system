<?php

namespace App\Livewire\System;

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
        try {
            $pdo = DB::connection()->getPdo();
            $dbVersion = $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
        } catch (\Exception $e) {
            $dbVersion = 'Connection Error';
        }

        return view('livewire.system.info', [
            'phpVersion' => phpversion(),
            'laravelVersion' => app()->version(),
            'serverSoftware' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'databaseConnection' => config('database.default'),
            'databaseVersion' => $dbVersion,
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
        ]);
    }
}
