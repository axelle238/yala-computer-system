<?php

namespace App\Livewire\Services;

use App\Models\ServiceTicket;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Service Kanban Board')]
class Kanban extends Component
{
    public $statuses = [
        'pending' => 'Pending',
        'diagnosing' => 'Diagnosing',
        'waiting_part' => 'Waiting Part',
        'repairing' => 'Repairing',
        'ready' => 'Ready for Pickup',
    ];

    public function updateStatus($ticketId, $newStatus)
    {
        $ticket = ServiceTicket::findOrFail($ticketId);
        
        // Basic validation
        if (!array_key_exists($newStatus, $this->statuses) && $newStatus !== 'picked_up' && $newStatus !== 'cancelled') {
            return;
        }

        $oldStatus = $ticket->status;
        $ticket->update(['status' => $newStatus]);
        
        // Log activity is handled by Trait usually, but we can add specific flash message
        $this->dispatch('notify', message: "Ticket #{$ticket->ticket_number} moved to " . ucfirst(str_replace('_', ' ', $newStatus)));
    }

    public function render()
    {
        // Get all active tickets grouped by status
        // We exclude 'picked_up' and 'cancelled' from the main board to keep it clean, 
        // or maybe show 'ready' as the last column.
        
        $tickets = ServiceTicket::whereIn('status', array_keys($this->statuses))
            ->with(['technician', 'items'])
            ->orderBy('created_at') // FIFO
            ->get()
            ->groupBy('status');

        return view('livewire.services.kanban', [
            'tickets' => $tickets
        ]);
    }
}
