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
    // Input Pencarian
    public $nomorTiket = '';
    public $verifikasiNomor = ''; // Nomor HP untuk keamanan

    // Hasil
    public $hasil = null;
    public $riwayatProgres = [];

    /**
     * Melakukan pelacakan tiket servis.
     */
    public function lacak()
    {
        $this->validate([
            'nomorTiket' => 'required|string|min:5',
            'verifikasiNomor' => 'required|string|min:4', 
        ], [
            'nomorTiket.required' => 'Nomor tiket wajib diisi.',
            'verifikasiNomor.required' => 'Masukkan nomor HP untuk verifikasi.',
        ]);

        // Cari tiket yang cocok dengan Nomor Tiket DAN Nomor HP (Match sebagian)
        $tiket = ServiceTicket::with(['teknisi', 'logProgres' => function($q) {
                // Hanya ambil log yang publik
                $q->where('is_publik', true)->orderBy('created_at', 'desc');
            }])
            ->where('ticket_number', $this->nomorTiket)
            ->where('customer_phone', 'like', '%'.$this->verifikasiNomor.'%')
            ->first();

        if (! $tiket) {
            $this->addError('nomorTiket', 'Data tidak ditemukan. Periksa kembali Nomor Tiket dan Nomor HP Anda.');
            $this->hasil = null;
            $this->riwayatProgres = [];

            return;
        }

        $this->hasil = $tiket;
        $this->riwayatProgres = $tiket->logProgres;
    }

    public function render()
    {
        return view('livewire.front.track-service');
    }
}