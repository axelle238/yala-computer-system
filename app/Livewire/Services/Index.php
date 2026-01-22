<?php

namespace App\Livewire\Services;

use App\Models\ServiceTicket;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Service Kanban - Yala Computer')]
class Index extends Component
{
    public $statuses = [
        'pending' => 'Menunggu Antrian',
        'diagnosing' => 'Pengecekan',
        'waiting_part' => 'Menunggu Sparepart',
        'repairing' => 'Sedang Diperbaiki',
        'ready' => 'Siap Diambil',
        'picked_up' => 'Selesai/Diambil'
    ];

    public function updateStatus($ticketId, $newStatus)
    {
        $ticket = ServiceTicket::find($ticketId);
        if ($ticket && array_key_exists($newStatus, $this->statuses)) {
            $ticket->update(['status' => $newStatus]);
            
            // Notify
            $this->dispatch('notify', message: "Status tiket #{$ticket->ticket_number} diperbarui!", type: 'success');
        }
    }

    public function render()
    {
        $tickets = ServiceTicket::with('technician')
            ->where('status', '!=', 'cancelled') // Hide cancelled from board
            ->latest()
            ->get()
            ->groupBy('status');

        return view('livewire.services.index', [
            'tickets' => $tickets
        ]);
    }
}
