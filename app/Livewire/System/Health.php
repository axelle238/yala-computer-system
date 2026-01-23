<?php

namespace App\Livewire\System;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('System Health - Yala Computer')]
class Health extends Component
{
    public function render()
    {
        // 1. Database Check
        try {
            DB::connection()->getPdo();
            $dbStatus = 'OK';
            $dbSize = DB::table('information_schema.tables')
                ->selectRaw('SUM(data_length + index_length) / 1024 / 1024 as size')
                ->where('table_schema', env('DB_DATABASE', 'yala_computer'))
                ->value('size');
        } catch (\Exception $e) {
            $dbStatus = 'ERROR: ' . $e->getMessage();
            $dbSize = 0;
        }

        // 2. Storage Check
        $storageStatus = is_writable(storage_path()) ? 'Writable' : 'Not Writable';
        $diskFree = disk_free_space(base_path()) / 1024 / 1024 / 1024; // GB
        $diskTotal = disk_total_space(base_path()) / 1024 / 1024 / 1024; // GB

        // 3. Cache Check
        $cacheStatus = Cache::store()->getStore() instanceof \Illuminate\Cache\ArrayStore ? 'Array (Non-Persistent)' : 'Active';

        // 4. Server Info
        $serverInfo = [
            'php_version' => phpversion(),
            'server_os' => php_uname('s') . ' ' . php_uname('r'),
            'laravel_version' => app()->version(),
            'timezone' => config('app.timezone'),
        ];

        return view('livewire.system.health', compact(
            'dbStatus', 'dbSize', 'storageStatus', 'diskFree', 'diskTotal', 'cacheStatus', 'serverInfo'
        ));
    }
}
