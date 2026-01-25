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
    public $filterUserType = ''; // 'customer' or 'employee'

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterAction() { $this->resetPage(); }
    public function updatingFilterUserType() { $this->resetPage(); }

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->where(function($q) {
                $q->where('description', 'like', '%'.$this->search.'%')
                  ->orWhere('action', 'like', '%'.$this->search.'%')
                  ->orWhereHas('user', function($u) {
                      $u->where('name', 'like', '%'.$this->search.'%');
                  });
            })
            ->when($this->filterAction, fn($q) => $q->where('action', $this->filterAction))
            ->when($this->filterUserType, function($q) {
                if ($this->filterUserType === 'customer') {
                    $q->whereHas('user', fn($u) => $u->where('role', 'customer'));
                } elseif ($this->filterUserType === 'employee') {
                    $q->whereHas('user', fn($u) => $u->whereIn('role', ['admin', 'technician', 'cashier']));
                }
            })
            ->latest()
            ->paginate(15);

        return view('livewire.activity-logs.index', ['logs' => $logs]);
    }
}
