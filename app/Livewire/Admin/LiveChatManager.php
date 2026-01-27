<?php

namespace App\Livewire\Admin;

use App\Models\PesanObrolan;
use App\Models\SesiObrolan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Pusat Bantuan & Live Chat - Yala Computer')]
class LiveChatManager extends Component
{
    use WithPagination;

    // Properti State
    public $sesiTerpilihId = null;

    public $isiPesan = '';

    public $cariPelanggan = '';

    // Event Listener
    protected $listeners = ['refreshChat' => '$refresh'];

    /**
     * Inisialisasi komponen.
     */
    public function mount()
    {
        // Otomatis pilih sesi terbaru jika ada
        $sesiTerakhir = SesiObrolan::latest()->first();
        if ($sesiTerakhir) {
            $this->pilihSesi($sesiTerakhir->id);
        }
    }

    /**
     * Memilih sesi obrolan aktif.
     */
    public function pilihSesi($id)
    {
        $this->sesiTerpilihId = $id;
        $this->tandaiTelahDibaca($id);
    }

    /**
     * Menandai pesan pelanggan sebagai telah dibaca.
     */
    public function tandaiTelahDibaca($idSesi)
    {
        PesanObrolan::where('id_sesi', $idSesi)
            ->where('is_balasan_admin', false)
            ->where('is_dibaca', false)
            ->update(['is_dibaca' => true]);
    }

    /**
     * Mengirim balasan admin ke pelanggan.
     */
    public function kirimBalasan()
    {
        $this->validate([
            'isiPesan' => 'required|string|min:1',
        ], [
            'isiPesan.required' => 'Pesan tidak boleh kosong.',
        ]);

        if (! $this->sesiTerpilihId) {
            return;
        }

        PesanObrolan::create([
            'id_sesi' => $this->sesiTerpilihId,
            'id_pengguna' => Auth::id(),
            'is_balasan_admin' => true,
            'isi' => $this->isiPesan,
            'is_dibaca' => true,
        ]);

        // Perbarui timestamp update sesi untuk menaikkan posisi di list
        SesiObrolan::find($this->sesiTerpilihId)->touch();

        $this->isiPesan = '';

        // Trigger scroll otomatis di frontend
        $this->dispatch('gulir-ke-bawah');
    }

    /**
     * Render antarmuka Live Chat.
     */
    public function render()
    {
        // Ambil daftar sesi dengan relasi pelanggan dan tier loyalitas (Integrasi CRM)
        $daftarSesi = SesiObrolan::with(['pelanggan', 'pesanTerakhir'])
            ->when($this->cariPelanggan, function ($query) {
                $query->whereHas('pelanggan', function ($q) {
                    $q->where('name', 'like', '%'.$this->cariPelanggan.'%');
                })->orWhere('topik', 'like', '%'.$this->cariPelanggan.'%');
            })
            ->latest('updated_at')
            ->get();

        $daftarPesan = [];
        $sesiAktif = null;

        if ($this->sesiTerpilihId) {
            $sesiAktif = SesiObrolan::with('pelanggan')->find($this->sesiTerpilihId);
            if ($sesiAktif) {
                $daftarPesan = PesanObrolan::where('id_sesi', $this->sesiTerpilihId)
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        }

        return view('livewire.admin.live-chat-manager', [
            'daftarSesi' => $daftarSesi,
            'daftarPesan' => $daftarPesan,
            'sesiAktif' => $sesiAktif,
        ]);
    }
}
