<?php

namespace App\Livewire\Services;

use App\Models\ProgresServis;
use App\Models\ServiceTicket;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

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

    public function perbaruiStatus($idTiket, $statusBaru)
    {
        $tiket = ServiceTicket::findOrFail($idTiket);

        // Validasi dasar
        if (! array_key_exists($statusBaru, $this->statuses) && $statusBaru !== 'picked_up' && $statusBaru !== 'cancelled') {
            return;
        }

        if ($tiket->status === $statusBaru) {
            return;
        }

        $statusLama = $tiket->status;
        $tiket->update(['status' => $statusBaru]);

        // KONSISTENSI: Buat Log Progres
        ProgresServis::create([
            'id_tiket_servis' => $tiket->id,
            'status' => $statusBaru,
            'deskripsi' => 'Status diperbarui via Papan Kanban dari '.ucfirst(str_replace('_', ' ', $statusLama)).' ke '.ucfirst(str_replace('_', ' ', $statusBaru)),
            'id_teknisi' => Auth::id() ?? 1, // Fallback ke admin jika belum login
            'is_publik' => true,
        ]);

        $this->dispatch('notify', message: "Tiket #{$tiket->ticket_number} dipindahkan ke ".($this->statuses[$statusBaru] ?? $statusBaru));
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
            'tiket' => $tiket,
        ]);
    }
}
