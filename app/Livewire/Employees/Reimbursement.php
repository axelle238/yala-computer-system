<?php

namespace App\Livewire\Employees;

use App\Models\ActivityLog;
use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\Reimbursement as ModelKlaim;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen Klaim Biaya (Reimbursement) - Yala Computer')]
class Reimbursement extends Component
{
    use WithFileUploads, WithPagination;

    // Properti Formulir
    public $jumlah;

    public $tanggal;

    public $kategori = 'Transportasi';

    public $keterangan;

    public $bukti;

    /**
     * Inisialisasi komponen.
     */
    public function mount()
    {
        $this->tanggal = date('Y-m-d');
    }

    /**
     * Menyimpan pengajuan klaim baru oleh karyawan.
     */
    public function simpan()
    {
        $this->validate([
            'jumlah' => 'required|numeric|min:100',
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'keterangan' => 'required|string|min:5',
            'bukti' => 'nullable|image|max:2048',
        ], [
            'jumlah.min' => 'Jumlah klaim minimal Rp 100.',
            'keterangan.min' => 'Keterangan terlalu singkat.',
        ]);

        $jalurBukti = $this->bukti ? $this->bukti->store('reimbursements', 'public') : null;

        ModelKlaim::create([
            'claim_number' => 'KLAIM-'.date('Ymd').'-'.strtoupper(Str::random(4)),
            'user_id' => Auth::id(),
            'date' => $this->tanggal,
            'category' => $this->kategori,
            'amount' => $this->jumlah,
            'description' => $this->keterangan,
            'proof_file' => $jalurBukti,
            'status' => 'pending',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'model_type' => ModelKlaim::class,
            'description' => 'Mengajukan klaim reimbursement baru sebesar Rp '.number_format($this->jumlah),
            'ip_address' => request()->ip(),
        ]);

        $this->reset(['jumlah', 'keterangan', 'bukti']);
        $this->dispatch('notify', message: 'Klaim biaya berhasil diajukan dan menunggu persetujuan.', type: 'success');
    }

    /**
     * Menyetujui klaim dan mengintegrasikannya dengan kasir (Kompleks).
     */
    public function setujui($id)
    {
        $pengguna = Auth::user();

        // Cek Otoritas (Admin atau Owner)
        if (! ($pengguna->peran && in_array($pengguna->peran->nama, ['Admin', 'Owner']))) {
            $this->dispatch('notify', message: 'Anda tidak memiliki otoritas untuk menyetujui klaim.', type: 'error');

            return;
        }

        $klaim = ModelKlaim::findOrFail($id);

        if ($klaim->status !== 'pending') {
            $this->dispatch('notify', message: 'Klaim sudah diproses sebelumnya.', type: 'warning');

            return;
        }

        // Integrasi dengan Kasir Toko jika ada shift yang buka
        $kasirAktif = CashRegister::where('user_id', Auth::id())->where('status', 'open')->first();

        DB::transaction(function () use ($klaim, $kasirAktif) {
            $klaim->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
            ]);

            // Jika ada kasir buka, otomatis catat transaksi keluar
            if ($kasirAktif) {
                CashTransaction::create([
                    'cash_register_id' => $kasirAktif->id,
                    'transaction_number' => 'KLAIM-BAYAR-'.$klaim->id,
                    'type' => 'out',
                    'category' => 'expense',
                    'amount' => $klaim->amount,
                    'description' => "Pembayaran Reimbursement #{$klaim->claim_number} ({$klaim->user->name})",
                    'reference_id' => $klaim->id,
                    'reference_type' => ModelKlaim::class,
                    'created_by' => Auth::id(),
                ]);
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'update',
                'model_type' => ModelKlaim::class,
                'model_id' => $klaim->id,
                'description' => "Menyetujui klaim reimbursement #{$klaim->claim_number}",
                'ip_address' => request()->ip(),
            ]);
        });

        $pesan = $kasirAktif
            ? 'Klaim disetujui dan saldo kasir telah dikurangi otomatis.'
            : 'Klaim disetujui. Catatan: Saldo kasir tidak terpotong karena tidak ada shift yang dibuka.';

        $this->dispatch('notify', message: $pesan, type: 'success');
    }

    /**
     * Menolak pengajuan klaim.
     */
    public function tolak($id)
    {
        $pengguna = Auth::user();
        if (! ($pengguna->peran && in_array($pengguna->peran->nama, ['Admin', 'Owner']))) {
            return;
        }

        $klaim = ModelKlaim::findOrFail($id);
        $klaim->update(['status' => 'rejected', 'approved_by' => Auth::id()]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'model_type' => ModelKlaim::class,
            'model_id' => $klaim->id,
            'description' => "Menolak klaim reimbursement #{$klaim->claim_number}",
            'ip_address' => request()->ip(),
        ]);

        $this->dispatch('notify', message: 'Pengajuan klaim telah ditolak.', type: 'info');
    }

    /**
     * Render tampilan.
     */
    public function render()
    {
        $kueri = ModelKlaim::with(['user', 'manager'])->latest();

        // Karyawan biasa hanya melihat klaim miliknya sendiri
        $pengguna = Auth::user();
        $isManajer = ($pengguna->peran && in_array($pengguna->peran->nama, ['Admin', 'Owner']));

        if (! $isManajer) {
            $kueri->where('user_id', Auth::id());
        }

        return view('livewire.employees.reimbursement', [
            'daftarKlaim' => $kueri->paginate(10),
            'isManajer' => $isManajer,
        ]);
    }
}
