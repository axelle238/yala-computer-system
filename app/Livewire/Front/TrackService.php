<?php

namespace App\Livewire\Front;

use App\Models\ServiceTicket;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Cek Status Service - Yala Computer')]
class TrackService extends Component
{
    public $search_ticket = '';

    public $phone_verification = '';

    public $result = null;

    public $timeline = [];

    public function track()
    {
        $this->validate([
            'search_ticket' => 'required|string|min:5',
            'phone_verification' => 'required|string|min:4', // Last 4 digits or full phone
        ]);

        // Find ticket that matches Ticket Number AND Phone (fuzzy match for phone)
        $ticket = ServiceTicket::with(['items', 'technician', 'histories.user'])
            ->where('ticket_number', $this->search_ticket)
            ->where('customer_phone', 'like', '%'.$this->phone_verification.'%')
            ->first();

        if (! $ticket) {
            $this->addError('search_ticket', 'Data tidak ditemukan. Pastikan Nomor Tiket dan Nomor HP sesuai.');
            $this->result = null;

            return;
        }

        $this->result = $ticket;

        // Build Timeline
        // Default standard milestones
        $standardSteps = [
            'pending' => 'Menunggu Antrian',
            'diagnosing' => 'Pengecekan (Diagnosa)',
            'repairing' => 'Dalam Pengerjaan',
            'ready' => 'Selesai / Siap Diambil',
            'picked_up' => 'Diambil',
        ];

        // Merge actual history
        $this->timeline = $ticket->histories->sortByDesc('created_at');
    }

    public function render()
    {
        return view('livewire.front.track-service');
    }
}
