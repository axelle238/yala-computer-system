<?php

namespace App\Livewire\Admin;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Manajemen Tugas Internal')]
class PengelolaTugas extends Component
{
    public $daftarTugas = [];

    // Status Tampilan
    public $aksiAktif = null; // null, 'buat'

    public $judul;

    public $deskripsi;

    public $prioritas = 'medium';

    public $idPenerima;

    public function mount()
    {
        $this->muatUlangTugas();
    }

    public function muatUlangTugas()
    {
        $this->daftarTugas = Task::with('assignee')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function bukaPanelBuat()
    {
        $this->reset(['judul', 'deskripsi', 'prioritas', 'idPenerima']);
        $this->aksiAktif = 'buat';
    }

    public function tutupPanel()
    {
        $this->aksiAktif = null;
    }

    public function simpan()
    {
        $this->validate([
            'judul' => 'required|min:3',
            'prioritas' => 'required|in:low,medium,high,urgent',
            'deskripsi' => 'nullable|string',
            'idPenerima' => 'nullable|exists:users,id',
        ], [
            'judul.required' => 'Judul tugas wajib diisi.',
            'judul.min' => 'Judul minimal 3 karakter.',
        ]);

        Task::create([
            'title' => $this->judul,
            'description' => $this->deskripsi,
            'priority' => $this->prioritas,
            'assigned_to' => $this->idPenerima ?? Auth::id(),
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);

        $this->dispatch('notify', message: 'Tugas berhasil dibuat!', type: 'success');

        $this->tutupPanel();
        $this->muatUlangTugas();
    }

    public function perbaruiStatus($idTugas, $statusBaru)
    {
        if (! in_array($statusBaru, ['pending', 'in_progress', 'completed', 'cancelled'])) {
            return;
        }

        Task::where('id', $idTugas)->update(['status' => $statusBaru]);

        $this->muatUlangTugas();
        $this->dispatch('notify', message: 'Status tugas diperbarui.', type: 'success');
    }

    public function render()
    {
        $semuaPengguna = User::all();

        return view('livewire.admin.pengelola-tugas', [
            'semuaPengguna' => $semuaPengguna,
            'tugasAntrian' => $this->daftarTugas->where('status', 'pending'),
            'tugasProses' => $this->daftarTugas->where('status', 'in_progress'),
            'tugasSelesai' => $this->daftarTugas->where('status', 'completed'),
        ]);
    }
}
