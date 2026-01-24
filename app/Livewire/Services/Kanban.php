<?php

namespace App\Livewire\Services;

use App\Models\ServiceTicket;
use App\Models\ServiceTicketProgress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
#[Title('Service Kanban Board')]
class Kanban extends Component
{
    public $statuses = [
        'pending' => 'Menunggu',
        'diagnosing' => 'Sedang Diagnosa',
        'waiting_part' => 'Tunggu Sparepart',
        'repairing' => 'Dalam Perbaikan',
        'ready' => 'Siap Diambil',
    ];

    public function updateStatus($ticketId, $newStatus)
    {
        $ticket = ServiceTicket::findOrFail($ticketId);
        
        // Basic validation
        if (!array_key_exists($newStatus, $this->statuses) && $newStatus !== 'picked_up' && $newStatus !== 'cancelled') {
            return;
        }

        if ($ticket->status === $newStatus) return;

        $oldStatus = $ticket->status;
        $ticket->update(['status' => $newStatus]);
        
        // CONSISTENCY: Create Progress Log
        ServiceTicketProgress::create([
            'service_ticket_id' => $ticket->id,
            'status_label' => $newStatus,
            'description' => "Status diperbarui via Kanban Board dari " . ucfirst(str_replace('_', ' ', $oldStatus)) . " ke " . ucfirst(str_replace('_', ' ', $newStatus)),
            'technician_id' => Auth::id() ?? 1, // Fallback to admin if not logged in (should be auth)
            'is_public' => true,
        ]);

        $this->dispatch('notify', message: "Ticket #{$ticket->ticket_number} dipindahkan ke " . $this->statuses[$newStatus] ?? $newStatus);
    }

    public function render()
    {
        // Get all active tickets grouped by status
        $tickets = ServiceTicket::whereIn('status', array_keys($this->statuses))
            ->with(['technician', 'customerMember'])
            ->orderBy('priority', 'desc') // Asumsi ada priority field, atau created_at
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('status');

        return view('livewire.services.kanban', [
            'tickets' => $tickets
        ]);
    }
}