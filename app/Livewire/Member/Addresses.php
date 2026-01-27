<?php

namespace App\Livewire\Member;

use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.member')]
#[Title('Buku Alamat - Yala Computer')]
class Addresses extends Component
{
    /**
     * Data daftar alamat pengguna.
     */
    public $daftarAlamat;

    /**
     * Flag untuk menampilkan form input.
     */
    public $tampilkanForm = false;

    /**
     * ID alamat yang sedang diubah.
     */
    public $idAlamat;

    // Properti Form
    public $label = 'Rumah';

    public $namaPenerima;

    public $nomorTelepon;

    public $barisAlamat;

    public $kota;

    public $isUtama = false;

    /**
     * Daftar kota statis (ideal dari API RajaOngkir).
     */
    public $daftarKota = [
        'Jakarta', 'Bogor', 'Depok', 'Tangerang', 'Bekasi', 'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta', 'Medan', 'Denpasar', 'Makassar',
    ];

    public function mount()
    {
        $this->muatAlamat();
    }

    /**
     * Mengambil daftar alamat terbaru dari database.
     */
    public function muatAlamat()
    {
        $this->daftarAlamat = UserAddress::where('user_id', Auth::id())
            ->orderBy('is_primary', 'desc')
            ->get();
    }

    /**
     * Mempersiapkan form untuk membuat alamat baru.
     */
    public function buat()
    {
        $this->reset(['idAlamat', 'label', 'namaPenerima', 'nomorTelepon', 'barisAlamat', 'kota', 'isUtama']);
        $this->namaPenerima = Auth::user()->name;
        $this->nomorTelepon = Auth::user()->phone;
        $this->tampilkanForm = true;
    }

    /**
     * Mempersiapkan form untuk mengubah alamat yang sudah ada.
     */
    public function ubah($id)
    {
        $alamat = UserAddress::where('user_id', Auth::id())->findOrFail($id);
        $this->idAlamat = $alamat->id;
        $this->label = $alamat->label;
        $this->namaPenerima = $alamat->recipient_name;
        $this->nomorTelepon = $alamat->phone_number;
        $this->barisAlamat = $alamat->address_line;
        $this->kota = $alamat->city;
        $this->isUtama = $alamat->is_primary;
        $this->tampilkanForm = true;
    }

    /**
     * Menyimpan data alamat ke database.
     */
    public function simpan()
    {
        $this->validate([
            'label' => 'required',
            'namaPenerima' => 'required',
            'nomorTelepon' => 'required',
            'barisAlamat' => 'required',
            'kota' => 'required',
        ], [
            'label.required' => 'Label alamat wajib diisi.',
            'namaPenerima.required' => 'Nama penerima wajib diisi.',
            'nomorTelepon.required' => 'Nomor telepon wajib diisi.',
            'barisAlamat.required' => 'Alamat lengkap wajib diisi.',
            'kota.required' => 'Kota wajib dipilih.',
        ]);

        if ($this->isUtama) {
            // Nonaktifkan alamat utama lainnya
            UserAddress::where('user_id', Auth::id())->update(['is_primary' => false]);
        }

        UserAddress::updateOrCreate(['id' => $this->idAlamat], [
            'user_id' => Auth::id(),
            'label' => $this->label,
            'recipient_name' => $this->namaPenerima,
            'phone_number' => $this->nomorTelepon,
            'address_line' => $this->barisAlamat,
            'city' => $this->kota,
            'is_primary' => $this->isUtama,
        ]);

        $this->tampilkanForm = false;
        $this->muatAlamat();
        $this->dispatch('notify', message: 'Alamat berhasil disimpan.', type: 'success');
    }

    /**
     * Menghapus alamat dari database.
     */
    public function hapus($id)
    {
        UserAddress::where('user_id', Auth::id())->where('id', $id)->delete();
        $this->muatAlamat();
        $this->dispatch('notify', message: 'Alamat telah dihapus.', type: 'success');
    }

    public function render()
    {
        return view('livewire.member.addresses');
    }
}
