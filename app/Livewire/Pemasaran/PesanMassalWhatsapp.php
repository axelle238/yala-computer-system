<?php

namespace App\Livewire\Pemasaran;

use App\Models\User;
use App\Models\WhatsappBlast as WhatsappBlastModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Pesan Massal WhatsApp - Yala Computer')]
class PesanMassalWhatsapp extends Component
{
    use WithPagination;

    public $pencarian = '';

    // Properti Formulir
    public $aksiAktif = null; // null, 'buat'
    public $namaKampanye;
    public $targetAudiens = 'all'; // all, loyal, inactive
    public $pesanTemplate;
    public $jadwalKirim;

    public function updatingPencarian()
    {
        $this->resetPage();
    }

    public function bukaPanelBuat()
    {
        $this->reset(['namaKampanye', 'targetAudiens', 'pesanTemplate', 'jadwalKirim']);
        $this->aksiAktif = 'buat';
    }

    public function tutupPanel()
    {
        $this->aksiAktif = null;
    }

    public function simpan()
    {
        $this->validate([
            'namaKampanye' => 'required|string|max:255',
            'pesanTemplate' => 'required|string|min:10',
            'targetAudiens' => 'required|in:all,loyal,inactive',
            'jadwalKirim' => 'nullable|date|after:now',
        ], [
            'namaKampanye.required' => 'Nama kampanye wajib diisi.',
            'pesanTemplate.required' => 'Pesan tidak boleh kosong.',
            'jadwalKirim.after' => 'Jadwal kirim harus di masa depan.',
        ]);

        // Hitung estimasi penerima
        $totalPenerima = $this->hitungPenerima($this->targetAudiens);

        WhatsappBlastModel::create([
            'campaign_name' => $this->namaKampanye,
            'message_template' => $this->pesanTemplate,
            'target_audience' => $this->targetAudiens,
            'scheduled_at' => $this->jadwalKirim,
            'status' => $this->jadwalKirim ? 'pending' : 'processing',
            'total_recipients' => $totalPenerima,
            'created_by' => Auth::id(),
        ]);

        $this->dispatch('notify', message: 'Kampanye WhatsApp berhasil dibuat.', type: 'success');
        $this->tutupPanel();
    }

    public function hitungPenerima($target)
    {
        $query = User::whereNotNull('phone');

        if ($target === 'loyal') {
            $query->where('total_spent', '>=', 10000000);
        } elseif ($target === 'inactive') {
            $query->where('last_purchase_at', '<', now()->subMonths(3));
        }

        return $query->count();
    }

    public function render()
    {
        $daftarPesan = WhatsappBlastModel::query()
            ->when($this->pencarian, function ($q) {
                $q->where('campaign_name', 'like', '%'.$this->pencarian.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.pemasaran.pesan-massal-whatsapp', [
            'daftarPesan' => $daftarPesan,
        ]);
    }
}