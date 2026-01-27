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
#[Title('Pusat Kampanye WhatsApp - Yala Computer')]
class PesanMassalWhatsapp extends Component
{
    use WithPagination;

    // Filter Tampilan
    public $kataKunciCari = '';

    // Status Panel
    public $aksiAktif = null; // null, 'buat'

    // Data Formulir
    public $namaKampanye;
    public $kriteriaAudiens = 'semua'; // semua, loyal, tidak_aktif, gold_platinum
    public $templatePesan;
    public $jadwalKirim;

    /**
     * Reset halaman saat mencari.
     */
    public function updatingKataKunciCari()
    {
        $this->resetPage();
    }

    /**
     * Membuka formulir pembuatan kampanye baru.
     */
    public function bukaFormBuat()
    {
        $this->reset(['namaKampanye', 'kriteriaAudiens', 'templatePesan', 'jadwalKirim']);
        $this->aksiAktif = 'buat';
    }

    /**
     * Menutup formulir kampanye.
     */
    public function tutupForm()
    {
        $this->aksiAktif = null;
    }

    /**
     * Validasi dan simpan kampanye ke antrian.
     */
    public function simpanKampanye()
    {
        $this->validate([
            'namaKampanye' => 'required|string|max:255',
            'templatePesan' => 'required|string|min:10',
            'kriteriaAudiens' => 'required|in:semua,loyal,tidak_aktif,gold_platinum',
            'jadwalKirim' => 'nullable|date|after:now',
        ], [
            'namaKampanye.required' => 'Judul kampanye wajib diisi.',
            'templatePesan.required' => 'Isi pesan template tidak boleh kosong.',
            'templatePesan.min' => 'Pesan minimal terdiri dari 10 karakter.',
            'jadwalKirim.after' => 'Waktu penjadwalan harus di masa mendatang.',
        ]);

        // Kalkulasi jumlah target penerima berdasarkan integrasi CRM
        $jumlahPenerima = $this->hitungEstimasiPenerima($this->kriteriaAudiens);

        if ($jumlahPenerima === 0) {
            $this->dispatch('notify', message: 'Gagal! Tidak ada data audiens yang masuk dalam kriteria tersebut.', type: 'error');
            return;
        }

        WhatsappBlastModel::create([
            'campaign_name' => $this->namaKampanye,
            'message_template' => $this->templatePesan,
            'target_audience' => $this->kriteriaAudiens,
            'scheduled_at' => $this->jadwalKirim,
            'status' => 'pending',
            'total_recipients' => $jumlahPenerima,
            'created_by' => Auth::id(),
        ]);

        $this->dispatch('notify', message: 'Kampanye WhatsApp berhasil didaftarkan.', type: 'success');
        $this->tutupForm();
    }

    /**
     * Memulai proses pengiriman pesan massal.
     */
    public function luncurkanSekarang($id)
    {
        $kampanye = WhatsappBlastModel::findOrFail($id);

        if ($kampanye->status !== 'pending') {
            return;
        }

        // Jalankan Job Latar Belakang
        \App\Jobs\SendWhatsappBlastJob::dispatch($kampanye);

        $this->dispatch('notify', message: "Kampanye '{$kampanye->campaign_name}' telah masuk antrian pengiriman.", type: 'success');
    }

    /**
     * Menghapus data kampanye.
     */
    public function hapusData($id)
    {
        $kampanye = WhatsappBlastModel::findOrFail($id);

        if ($kampanye->status === 'processing') {
            $this->dispatch('notify', message: 'Maaf, kampanye sedang berjalan dan tidak dapat dihapus.', type: 'error');
            return;
        }

        $kampanye->delete();
        $this->dispatch('notify', message: 'Data kampanye telah dibersihkan dari sistem.', type: 'success');
    }

    /**
     * Logika CRM: Menghitung jumlah audiens berdasarkan filter kompleks.
     */
    public function hitungEstimasiPenerima($kriteria)
    {
        $query = User::whereNotNull('phone')->where('phone', '!=', '');

        switch ($kriteria) {
            case 'loyal':
                // Minimal belanja 10jt
                $query->where('total_spent', '>=', 10000000);
                break;
            case 'tidak_aktif':
                // Tidak belanja > 3 bulan
                $query->where('last_purchase_at', '<', now()->subMonths(3));
                break;
            case 'gold_platinum':
                // Hanya tier tinggi
                $query->whereIn('loyalty_tier', ['Gold', 'Platinum']);
                break;
            default:
                // Semua pengguna dengan nomor HP
                break;
        }

        return $query->count();
    }

    /**
     * Pratinjau pesan dinamis.
     */
    public function getPratinjauPesanProperty()
    {
        if (! $this->templatePesan) {
            return 'Isi template pesan untuk melihat simulasi tampilan di sini...';
        }

        return str_replace(
            ['{{nama}}', '{{produk_terakhir}}', '{{poin}}'],
            ['[Budi Santoso]', '[Laptop Asus ROG]', '[1.250 Poin]'],
            $this->templatePesan
        );
    }

    public function render()
    {
        $daftarKampanye = WhatsappBlastModel::query()
            ->when($this->kataKunciCari, function ($q) {
                $q->where('campaign_name', 'like', '%'.$this->kataKunciCari.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.pemasaran.pesan-massal-whatsapp', [
            'daftarKampanye' => $daftarKampanye,
        ]);
    }
}