<?php

namespace App\Livewire\Services;

use App\Models\ServiceTicket;
use App\Models\ServiceTicketProgress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
#[Title('Papan Kanban Servis - Yala Computer')]
class Kanban extends Component
{
    public $statuses = [
        'pending' => 'Menunggu',
        'diagnosing' => 'Sedang Diagnosa',
        'waiting_part' => 'Tunggu Sparepart',
        'repairing' => 'Dalam Perbaikan',
        'ready' => 'Siap Diambil',
    ];

    public function updateStatus($idTiket, $statusBaru)
    {
        $tiket = ServiceTicket::findOrFail($idTiket);
        
        // Validasi dasar
        if (!array_key_exists($statusBaru, $this->statuses) && $statusBaru !== 'picked_up' && $statusBaru !== 'cancelled') {
            return;
        }

        if ($tiket->status === $statusBaru) return;

        $statusLama = $tiket->status;
        $tiket->update(['status' => $statusBaru]);
        
        // KONSISTENSI: Buat Log Progres
        ServiceTicketProgress::create([
            'service_ticket_id' => $tiket->id,
            'status_label' => $statusBaru,
            'description' => "Status diperbarui via Papan Kanban dari " . ucfirst(str_replace('_', ' ', $statusLama)) . " ke " . ucfirst(str_replace('_', ' ', $statusBaru)),
            'technician_id' => Auth::id() ?? 1, // Fallback ke admin jika belum login
            'is_public' => true,
        ]);

        $this->dispatch('notify', message: "Tiket #{$tiket->ticket_number} dipindahkan ke " . ($this->statuses[$statusBaru] ?? $statusBaru));
    }

    public function render()
    {
        // Ambil semua tiket aktif dikelompokkan berdasarkan status
        $tiket = ServiceTicket::whereIn('status', array_keys($this->statuses))
            ->with(['technician', 'customerMember'])
            ->orderBy('created_at', 'asc') // Urutkan berdasarkan tanggal dibuat (FIFO)
            ->get()
            ->groupBy('status');

        return view('livewire.services.kanban', [
            'tickets' => $tiket // Tetap gunakan 'tickets' agar view blade tidak error, nanti blade-nya disesuaikan
        ]);
    }
}
