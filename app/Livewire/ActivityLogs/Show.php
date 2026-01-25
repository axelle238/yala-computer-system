<?php

namespace App\Livewire\ActivityLogs;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Detail Log Aktivitas - Yala Computer')]
class Show extends Component
{
    public $log;

    public function mount($id)
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }
        $this->log = ActivityLog::with('user')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.activity-logs.show');
    }
}
