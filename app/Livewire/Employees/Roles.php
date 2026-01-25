<?php

namespace App\Livewire\Employees;

use App\Models\Peran;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Manajemen Peran & Hak Akses - Yala Computer')]
class Roles extends Component
{
    /**
     * Peta daftar hak akses sistem.
     */
    public $petaHakAkses = [];

    /**
     * Flag untuk menampilkan form input.
     */
    public $tampilkanForm = false;

    // Properti Form
    public $idPeran;

    public $nama;

    public $hakAksesTerpilih = [];

    public function mount()
    {
        // Definisikan peta hak akses dalam Bahasa Indonesia
        $this->petaHakAkses = [
            'Penjualan (POS)' => ['akses_pos', 'proses_refund'],
            'Inventaris' => ['lihat_produk', 'tambah_produk', 'ubah_produk', 'sesuaikan_stok'],
            'Keuangan' => ['lihat_laporan', 'kelola_pengeluaran', 'tutup_kasir'],
            'SDM' => ['lihat_karyawan', 'kelola_peran', 'lihat_penggajian'],
            'Sistem' => ['perbarui_pengaturan', 'lihat_log', 'cadangkan_basis_data'],
        ];
    }

    /**
     * Mempersiapkan form untuk membuat peran baru.
     */
    public function buat()
    {
        $this->reset(['idPeran', 'nama', 'hakAksesTerpilih']);
        $this->tampilkanForm = true;
    }

    /**
     * Mempersiapkan form untuk mengubah peran yang sudah ada.
     */
    public function ubah($id)
    {
        $peran = Peran::findOrFail($id);
        $this->idPeran = $peran->id;
        $this->nama = $peran->nama;
        $this->hakAksesTerpilih = $peran->hak_akses ?? [];
        $this->tampilkanForm = true;
    }

    /**
     * Menyimpan data peran ke database.
     */
    public function simpan()
    {
        $this->validate([
            'nama' => 'required|min:3|unique:peran,nama,'.$this->idPeran,
            'hakAksesTerpilih' => 'required|array|min:1',
        ], [
            'nama.required' => 'Nama peran wajib diisi.',
            'nama.unique' => 'Nama peran sudah digunakan.',
            'hakAksesTerpilih.required' => 'Pilih minimal satu hak akses.',
        ]);

        Peran::updateOrCreate(['id' => $this->idPeran], [
            'nama' => $this->nama,
            'hak_akses' => $this->hakAksesTerpilih,
        ]);

        $this->dispatch('notify', message: 'Peran & Hak Akses berhasil disimpan.', type: 'success');
        $this->tampilkanForm = false;
    }

    /**
     * Menghapus peran jika tidak ada pengguna yang menggunakannya.
     */
    public function hapus($id)
    {
        $peran = Peran::find($id);
        if ($peran->pengguna()->exists()) {
            $this->dispatch('notify', message: 'Gagal! Masih ada karyawan yang menggunakan peran ini.', type: 'error');

            return;
        }

        $peran->delete();
        $this->dispatch('notify', message: 'Peran telah dihapus.', type: 'success');
    }

    public function render()
    {
        $daftarPeran = Peran::latest()->get();

        return view('livewire.employees.roles', [
            'daftarPeran' => $daftarPeran,
            'petaHakAkses' => $this->petaHakAkses,
        ]);
    }
}
