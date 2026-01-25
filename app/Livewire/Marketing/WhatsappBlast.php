<?php

namespace App\Livewire\Marketing;

use App\Models\User;
use App\Models\WhatsappBlast as WhatsappBlastModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('WhatsApp Blast - Yala Computer')]
class WhatsappBlast extends Component
{
    use WithPagination;

    public $search = '';

    // Form Properties
    public $activeAction = null; // null, 'create'
    public $namaKampanye;
    public $targetAudiens = 'all'; // all, loyal, inactive
    public $pesanTemplate;
    public $jadwalKirim;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function bukaPanelBuat()
    {
        $this->reset(['namaKampanye', 'targetAudiens', 'pesanTemplate', 'jadwalKirim']);
        $this->activeAction = 'create';
    }

    public function tutupPanel()
    {
        $this->activeAction = null;
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
            'status' => $this->jadwalKirim ? 'pending' : 'processing', // Jika langsung kirim, set processing (mock)
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
            $query->where('total_spent', '>=', 10000000); // Contoh logika loyal
        } elseif ($target === 'inactive') {
            $query->where('last_purchase_at', '<', now()->subMonths(3));
        }

        return $query->count();
    }

    public function render()
    {
        $blasts = WhatsappBlastModel::query()
            ->when($this->search, function ($q) {
                $q->where('campaign_name', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.marketing.whatsapp-blast', [
            'blasts' => $blasts,
        ]);
    }
}
