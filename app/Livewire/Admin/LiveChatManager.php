<?php

namespace App\Livewire\Admin;

use App\Models\SesiObrolan;
use App\Models\PesanObrolan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.admin')]
#[Title('Live Chat Pelanggan - Yala Computer')]
class LiveChatManager extends Component
{
    use WithPagination;

    public $sesiTerpilihId = null;
    public $isiPesan = '';
    public $search = '';

    protected $listeners = ['refreshChat' => '$refresh'];

    public function mount()
    {
        // Auto select first session if exists
        $firstSession = SesiObrolan::latest()->first();
        if ($firstSession) {
            $this->pilihSesi($firstSession->id);
        }
    }

    public function pilihSesi($id)
    {
        $this->sesiTerpilihId = $id;
        $this->tandaiDibaca($id);
    }

    public function tandaiDibaca($sessionId)
    {
        PesanObrolan::where('id_sesi', $sessionId)
            ->where('is_balasan_admin', false)
            ->where('is_dibaca', false)
            ->update(['is_dibaca' => true]);
    }

    public function kirimPesan()
    {
        $this->validate([
            'isiPesan' => 'required|string|min:1',
        ]);

        if (!$this->sesiTerpilihId) return;

        PesanObrolan::create([
            'id_sesi' => $this->sesiTerpilihId,
            'id_pengguna' => Auth::id(),
            'is_balasan_admin' => true,
            'isi' => $this->isiPesan,
            'is_dibaca' => true // Pesan sendiri dianggap dibaca
        ]);

        $this->isiPesan = '';
        
        // Refresh chat
        $this->dispatch('scroll-to-bottom');
    }

    public function render()
    {
        $sesiList = SesiObrolan::with(['pelanggan', 'pesanTerakhir'])
            ->when($this->search, function ($query) {
                $query->whereHas('pelanggan', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('topik', 'like', '%' . $this->search . '%');
            })
            ->latest('updated_at') // Urutkan berdasarkan update terakhir (pesan baru)
            ->get(); // Tidak pakai pagination agar list tetap statis saat chat aktif

        $messages = [];
        $activeSession = null;

        if ($this->sesiTerpilihId) {
            $activeSession = SesiObrolan::with('pelanggan')->find($this->sesiTerpilihId);
            if ($activeSession) {
                $messages = PesanObrolan::where('id_sesi', $this->sesiTerpilihId)
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        }

        return view('livewire.admin.live-chat-manager', [
            'sesiList' => $sesiList,
            'messages' => $messages,
            'activeSession' => $activeSession
        ]);
    }
}
