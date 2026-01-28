<?php

namespace App\Livewire\Services;

use App\Models\ServiceHistory;
use App\Models\ServiceTicket;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Pusat Servis - Yala Computer')]
class Index extends Component
{
    public $statuses = [
        'pending' => 'Menunggu Antrian',
        'diagnosing' => 'Pengecekan',
        'waiting_part' => 'Menunggu Sparepart',
        'repairing' => 'Sedang Diperbaiki',
        'ready' => 'Siap Diambil',
        'picked_up' => 'Selesai/Diambil',
    ];

    public function updateStatus($ticketId, $newStatus)
    {
        $ticket = ServiceTicket::find($ticketId);
        if ($ticket && array_key_exists($newStatus, $this->statuses) && $ticket->status !== $newStatus) {
            $oldStatus = $ticket->status;
            $ticket->update(['status' => $newStatus]);

            // Log History
            ServiceHistory::create([
                'service_ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'status' => $newStatus,
                'notes' => 'Status diperbarui via Kanban Board (dari '.ucfirst($oldStatus).')',
            ]);

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

        $stats = [
            'queue' => ServiceTicket::whereIn('status', ['pending', 'diagnosing'])->count(),
            'working' => ServiceTicket::whereIn('status', ['repairing', 'waiting_part'])->count(),
            'ready' => ServiceTicket::where('status', 'ready')->count(),
            'revenue_month' => ServiceTicket::where('status', 'picked_up')
                ->whereMonth('updated_at', now()->month)
                ->sum('final_cost'),
        ];

        return view('livewire.services.index', [
            'tickets' => $tickets,
            'stats' => $stats,
        ]);
    }
}
