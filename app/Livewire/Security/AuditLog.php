<?php

namespace App\Livewire\Security;

use App\Models\ActivityLog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Audit Keamanan Lengkap')]
class AuditLog extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->where('description', 'like', '%'.$this->search.'%')
            ->orWhere('action', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(25);

        return view('livewire.security.audit-log', [
            'logs' => $logs,
        ]);
    }
}
