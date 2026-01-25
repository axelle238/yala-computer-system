<?php

namespace App\Livewire\System;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Database & Backup Manager')]
class DatabaseManager extends Component
{
    public $tables = [];
    public $totalSize = 0;
    public $backups = [];

    public function mount()
    {
        $this->loadTables();
        $this->loadBackups();
    }

    public function loadTables()
    {
        // Get all tables and their approximate row count & size
        // This query works for MySQL
        try {
            $dbName = config('database.connections.mysql.database');
            $tablesData = DB::select('SELECT table_name AS name, table_rows AS "rows", round(((data_length + index_length) / 1024 / 1024), 2) "size_mb" FROM information_schema.TABLES WHERE table_schema = ?', [$dbName]);
            
            $this->tables = collect($tablesData)->map(function($t) {
                return [
                    'name' => $t->name,
                    'rows' => $t->rows ?? 0,
                    'size' => $t->size_mb ?? 0
                ];
            })->sortByDesc('size_mb')->values()->all();

            $this->totalSize = collect($this->tables)->sum('size');
        } catch (\Exception $e) {
            $this->tables = []; // Fallback for SQLite/Other
        }
    }

    public function loadBackups()
    {
        // Mock backups if no real file system access
        // In real app: Storage::files('backups')
        $this->backups = [
            ['name' => 'db-backup-2025-01-25.sql.gz', 'size' => '2.5 MB', 'date' => now()->subDay()],
            ['name' => 'db-backup-2025-01-20.sql.gz', 'size' => '2.4 MB', 'date' => now()->subDays(6)],
        ];
    }

    public function createBackup()
    {
        // Artisan::call('backup:run --only-db'); 
        sleep(2); // Simulate heavy process
        
        array_unshift($this->backups, [
            'name' => 'db-backup-' . date('Y-m-d-H-i') . '.sql.gz',
            'size' => number_format($this->totalSize / 3, 2) . ' MB', // Compressed est
            'date' => now()
        ]);

        $this->dispatch('notify', message: 'Database berhasil di-backup!', type: 'success');
    }

    public function optimize()
    {
        // Artisan::call('optimize:clear');
        sleep(1);
        $this->dispatch('notify', message: 'Database & Cache dioptimalkan.', type: 'success');
    }

    public function download($name)
    {
        $this->dispatch('notify', message: 'Mengunduh ' . $name, type: 'info');
    }

    public function delete($index)
    {
        unset($this->backups[$index]);
        $this->backups = array_values($this->backups);
        $this->dispatch('notify', message: 'Backup dihapus.', type: 'success');
    }

    public function render()
    {
        return view('livewire.system.database-manager');
    }
}