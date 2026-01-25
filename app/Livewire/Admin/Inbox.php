<?php

namespace App\Livewire\Admin;

use App\Mail\ContactReplyMail;
use App\Models\PesanPelanggan;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Kotak Masuk & Pesan - Yala Computer')]
class Inbox extends Component
{
    use WithPagination;

    /**
     * Pesan yang sedang dibuka/dipilih.
     */
    public $pesanTerpilih = null;

    /**
     * Isi teks balasan untuk pelanggan.
     */
    public $isiBalasan = '';

    /**
     * Filter status pesan.
     */
    public $filter = 'semua'; // semua, baru, dibaca, dibalas

    /**
     * Memilih pesan untuk dibaca.
     */
    public function pilihPesan($id)
    {
        $this->pesanTerpilih = PesanPelanggan::findOrFail($id);

        if ($this->pesanTerpilih->status === PesanPelanggan::STATUS_BARU) {
            $this->pesanTerpilih->update(['status' => PesanPelanggan::STATUS_DIBACA]);
        }
    }

    /**
     * Mengirim balasan ke email pelanggan.
     */
    public function kirimBalasan()
    {
        $this->validate([
            'isiBalasan' => 'required|min:10',
        ], [
            'isiBalasan.required' => 'Isi balasan wajib diisi.',
            'isiBalasan.min' => 'Isi balasan minimal 10 karakter.',
        ]);

        if (! $this->pesanTerpilih) {
            return;
        }

        try {
            // Gunakan Mail untuk mengirim balasan
            Mail::to($this->pesanTerpilih->surel)
                ->send(new ContactReplyMail($this->isiBalasan, $this->pesanTerpilih->subjek));

            $this->pesanTerpilih->update(['status' => PesanPelanggan::STATUS_DIBALAS]);

            $this->dispatch('notify',
                message: 'Balasan berhasil dikirim ke '.$this->pesanTerpilih->surel,
                type: 'success'
            );
        } catch (\Exception $e) {
            $this->dispatch('notify',
                message: 'Gagal mengirim surel. Pastikan konfigurasi SMTP sudah benar.',
                type: 'error'
            );

            return;
        }

        $this->isiBalasan = '';
        $this->pesanTerpilih = null;
    }

    /**
     * Menghapus pesan pelanggan.
     */
    public function hapus($id)
    {
        PesanPelanggan::destroy($id);
        if ($this->pesanTerpilih && $this->pesanTerpilih->id == $id) {
            $this->pesanTerpilih = null;
        }
        $this->dispatch('notify', message: 'Pesan berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $query = PesanPelanggan::query();

        if ($this->filter === 'baru') {
            $query->where('status', PesanPelanggan::STATUS_BARU);
        } elseif ($this->filter === 'dibaca') {
            $query->where('status', PesanPelanggan::STATUS_DIBACA);
        } elseif ($this->filter === 'dibalas') {
            $query->where('status', PesanPelanggan::STATUS_DIBALAS);
        }

        $daftarPesan = $query->latest()->paginate(10);

        return view('livewire.admin.inbox', [
            'daftarPesan' => $daftarPesan,
        ]);
    }
}
