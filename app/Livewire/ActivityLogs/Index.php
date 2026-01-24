<?php

namespace App\Livewire\ActivityLogs;

use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Audit Log Aktivitas - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $actionFilter = '';

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->when($this->search, function($q) {
                $q->where('description', 'like', '%'.$this->search.'%')
                  ->orWhereHas('user', fn($sq) => $sq->where('name', 'like', '%'.$this->search.'%'));
            })
            ->when($this->actionFilter, fn($q) => $q->where('action', $this->actionFilter))
            ->latest()
            ->paginate(15);

        return view('livewire.activity-logs.index', ['logs' => $logs]);
    }
}