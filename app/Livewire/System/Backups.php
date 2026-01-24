<?php

namespace App\Livewire\System;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Database Backups - Yala Computer')]
class Backups extends Component
{
    public function getBackupsProperty()
    {
        $files = Storage::files('backups');
        $backups = [];
        
        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'path' => $file,
                'size' => $this->formatBytes(Storage::size($file)),
                'date' => date('Y-m-d H:i:s', Storage::lastModified($file)),
            ];
        }

        return collect($backups)->sortByDesc('date');
    }

    public function createBackup()
    {
        try {
            $filename = 'backup-' . date('Y-m-d-His') . '.sql';
            $path = storage_path('app/backups/' . $filename);
            
            // Ensure directory exists
            if (!Storage::exists('backups')) {
                Storage::makeDirectory('backups');
            }

            // Simple PHP MySQL Dump Logic
            $tables = DB::select('SHOW TABLES');
            $content = "-- Database Backup: " . config('app.name') . "\n";
            $content .= "-- Date: " . date('Y-m-d H:i:s') . "\n\n";
            $content .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $tableObj) {
                $table = reset($tableObj);
                
                // Create Table Structure
                $createTable = DB::select("SHOW CREATE TABLE `$table`")[0]->{'Create Table'};
                $content .= "DROP TABLE IF EXISTS `$table`;\n";
                $content .= $createTable . ";\n\n";

                // Insert Data
                $rows = DB::table($table)->get();
                foreach ($rows as $row) {
                    $values = array_map(function ($value) {
                        if (is_null($value)) return "NULL";
                        return "'" . addslashes($value) . "'";
                    }, (array) $row);
                    
                    $content .= "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
                }
                $content .= "\n";
            }

            $content .= "SET FOREIGN_KEY_CHECKS=1;\n";

            Storage::put('backups/' . $filename, $content);
            
            $this->dispatch('notify', message: 'Backup database berhasil dibuat!', type: 'success');
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
        $this->dispatch('notify', message: 'File backup dihapus.', type: 'success');
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
        return view('livewire.system.backups');
    }
}