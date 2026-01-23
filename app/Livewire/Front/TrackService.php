<?php

namespace App\Livewire\Front;

use App\Models\ServiceTicket;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.guest')] // Gunakan layout guest
#[Title('Cek Status Service - Yala Computer')]
class TrackService extends Component
{
    public $search_ticket; // Ticket ID (SRV-...)
    public $result = null;

    public function track()
    {
        $this->validate([
            'search_ticket' => 'required|string|min:5'
        ]);

        $this->result = ServiceTicket::with(['items', 'technician'])
            ->where('ticket_number', $this->search_ticket)
            ->first();

        if (!$this->result) {
            $this->addError('search_ticket', 'Tiket tidak ditemukan. Periksa kembali nomor tiket Anda.');
        }
    }

    public function render()
    {
        return view('livewire.front.track-service');
    }
}
