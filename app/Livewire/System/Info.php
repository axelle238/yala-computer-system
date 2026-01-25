<?php

namespace App\Livewire\System;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Informasi Sistem')]
class Info extends Component
{
    public function render()
    {
        return view('livewire.system.info', [
            'phpVersion' => phpversion(),
            'laravelVersion' => app()->version(),
            'serverSoftware' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'databaseConnection' => config('database.default'),
            'extensions' => get_loaded_extensions(),
            'os' => php_uname(),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
        ]);
    }
}
