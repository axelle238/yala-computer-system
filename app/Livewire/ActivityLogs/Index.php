<?php

namespace App\Livewire\ActivityLogs;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Audit Log - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedLog = null;
    public $isModalOpen = false;

    public function mount()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
    }

    public function openDetail($id)
    {
        $this->selectedLog = ActivityLog::with('user')->find($id);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedLog = null;
    }

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->where(function($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('model_type', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(20);

        return view('livewire.activity-logs.index', [
            'logs' => $logs
        ]);
    }
}
