<?php

namespace App\Livewire\Assembly;

use App\Models\ActivityLog;
use App\Models\PcAssembly;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Pengelola Produksi Rakitan - Yala Computer')]
class Manager extends Component
{
    use WithPagination;

    // Filter & Pencarian
    public $cari = '';
    public $filterStatus = '';

    // Status Tampilan (View State)
    public $aksiAktif = null; // null, 'detail'
    public $rakitanTerpilih = null;

    // Input Teknisi
    public $catatanTeknisi = '';
    public $skorBenchmark = '';
    public $spesifikasi = [];

    /**
     * Reset pagination saat mencari.
     */
    public function updatingCari()
    {
        $this->resetPage();
    }

    /**
     * Membuka panel detail rakitan.
     */
    public function bukaPanelDetail($id)
    {
        $this->rakitanTerpilih = PcAssembly::with(['pesanan.pengguna', 'pesanan.item.produk'])->find($id);

        if ($this->rakitanTerpilih) {
            $this->catatanTeknisi = $this->rakitanTerpilih->technician_notes;
            $this->skorBenchmark = $this->rakitanTerpilih->benchmark_score;

            // Parse spesifikasi dari snapshot JSON atau tebak dari item pesanan
            if ($this->rakitanTerpilih->specs_snapshot) {
                $this->spesifikasi = json_decode($this->rakitanTerpilih->specs_snapshot, true);
            } else {
                $this->spesifikasi = $this->rakitanTerpilih->pesanan->item->map(function ($item) {
                    return [
                        'nama' => $item->produk->name,
                        'qty' => $item->quantity,
                    ];
                })->toArray();
            }

            $this->aksiAktif = 'detail';
        }
    }

    /**
     * Menutup panel aksi.
     */
    public function tutupPanel()
    {
        $this->aksiAktif = null;
        $this->rakitanTerpilih = null;
    }

    /**
     * Memperbarui status pengerjaan rakitan.
     */
    public function perbaruiStatus($statusBaru)
    {
        if (! $this->rakitanTerpilih) {
            return;
        }

        $statusLama = $this->rakitanTerpilih->status;

        $this->rakitanTerpilih->update([
            'status' => $statusBaru,
            'technician_id' => Auth::id(),
        ]);

        // Catat waktu mulai/selesai secara otomatis
        if ($statusBaru === 'picking' && ! $this->rakitanTerpilih->started_at) {
            $this->rakitanTerpilih->update(['started_at' => now()]);
        }

        if ($statusBaru === 'completed') {
            $this->rakitanTerpilih->update(['completed_at' => now()]);
        }

        // Catat ke Log Aktivitas
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'model_type' => PcAssembly::class,
            'model_id' => $this->rakitanTerpilih->id,
            'description' => "Mengubah status rakitan #{$this->rakitanTerpilih->id} dari '{$statusLama}' menjadi '{$statusBaru}'.",
            'ip_address' => request()->ip(),
        ]);

        $this->dispatch('notify', message: 'Status rakitan berhasil diperbarui.', type: 'success');
    }

    /**
     * Menyimpan catatan teknisi dan skor performa.
     */
    public function simpanCatatan()
    {
        if (! $this->rakitanTerpilih) {
            return;
        }

        $this->rakitanTerpilih->update([
            'technician_notes' => $this->catatanTeknisi,
            'benchmark_score' => $this->skorBenchmark,
        ]);

        $this->dispatch('notify', message: 'Laporan teknis berhasil disimpan.', type: 'success');
    }

    public function render()
    {
        $daftarRakitan = PcAssembly::query()
            ->with(['pesanan', 'teknisi'])
            ->when($this->cari, function ($q) {
                $q->whereHas('pesanan', function ($sq) {
                    $sq->where('order_number', 'like', '%'.$this->cari.'%')
                        ->orWhere('guest_name', 'like', '%'.$this->cari.'%');
                })->orWhere('build_name', 'like', '%'.$this->cari.'%');
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.assembly.manager', [
            'daftarRakitan' => $daftarRakitan,
        ]);
    }
}