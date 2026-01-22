<?php

namespace App\Livewire\Services;

use App\Models\ServiceTicket;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Servis - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public function render()
    {
        $tickets = ServiceTicket::query()
            ->when($this->search, function($q) {
                $q->where('ticket_number', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('device_name', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function($q) {
                $q->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.services.index', [
            'tickets' => $tickets
        ]);
    }
}
