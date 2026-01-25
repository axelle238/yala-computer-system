<?php

namespace App\Livewire\ActivityLogs;

use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Audit Log & Keamanan')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterAction = '';

    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->where(function($q) {
                $q->where('description', 'like', '%'.$this->search.'%')
                  ->orWhere('action', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterAction, fn($q) => $q->where('action', $this->filterAction))
            ->latest()
            ->paginate(15);

        return view('livewire.activity-logs.index', ['logs' => $logs]);
    }
}
