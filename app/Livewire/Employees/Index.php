<?php

namespace App\Livewire\Employees;

use App\Models\User;
use App\Models\Peran;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen Karyawan - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    /**
     * Properti pencarian nama atau surel.
     */
    public $cari = '';

    /**
     * Flag untuk menampilkan form inputan.
     */
    public $tampilkanForm = false;
    
    // Properti Model Pengguna
    public $idPengguna;
    public $nama;
    public $surel;
    public $idPeran; // Menggunakan sistem Peran (RBAC)
    public $kataSandi;
    public $telepon;
    public $gaji;
    public $nomorKtp;
    public $alamatLengkap;

    /**
     * Mempersiapkan form untuk menambah karyawan baru.
     */
    public function buat()
    {
        $this->reset(['idPengguna', 'nama', 'surel', 'idPeran', 'kataSandi', 'telepon', 'gaji', 'nomorKtp', 'alamatLengkap']);
        $this->tampilkanForm = true;
    }

    /**
     * Mempersiapkan form untuk mengubah data karyawan yang sudah ada.
     */
    public function ubah($id)
    {
        $pengguna = User::findOrFail($id);
        $this->idPengguna = $pengguna->id;
        $this->nama = $pengguna->name;
        $this->surel = $pengguna->email;
        $this->idPeran = $pengguna->id_peran;
        $this->telepon = $pengguna->phone;
        $this->gaji = $pengguna->salary;
        $this->nomorKtp = $pengguna->nomor_ktp;
        $this->alamatLengkap = $pengguna->alamat_lengkap;
        $this->tampilkanForm = true;
    }

    /**
     * Menyimpan data karyawan ke dalam database.
     */
    public function simpan()
    {
        $aturan = [
            'nama' => 'required',
            'surel' => 'required|email|unique:users,email,'.$this->idPengguna,
            'idPeran' => 'required',
            'nomorKtp' => 'nullable|digits:16',
        ];

        if (!$this->idPengguna) {
            $aturan['kataSandi'] = 'required|min:6';
        }

        $this->validate($aturan, [
            'nama.required' => 'Nama wajib diisi.',
            'surel.required' => 'Surel wajib diisi.',
            'surel.unique' => 'Surel sudah terdaftar.',
            'idPeran.required' => 'Peran wajib dipilih.',
            'nomorKtp.digits' => 'Nomor KTP harus 16 digit.',
            'kataSandi.required' => 'Kata sandi wajib diisi.',
            'kataSandi.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        $data = [
            'name' => $this->nama,
            'email' => $this->surel,
            'id_peran' => $this->idPeran,
            'phone' => $this->telepon,
            'salary' => $this->gaji,
            'nomor_ktp' => $this->nomorKtp,
            'alamat_lengkap' => $this->alamatLengkap,
        ];

        if ($this->kataSandi) {
            $data['password'] = Hash::make($this->kataSandi);
        }

        User::updateOrCreate(['id' => $this->idPengguna], $data);

        $this->dispatch('notify', message: 'Data karyawan berhasil disimpan.', type: 'success');
        $this->tampilkanForm = false;
    }

    /**
     * Menghapus data karyawan dari database.
     */
    public function hapus($id)
    {
        if ($id == auth()->id()) {
            $this->dispatch('notify', message: 'Tidak dapat menghapus akun Anda sendiri.', type: 'error');
            return;
        }
        User::find($id)->delete();
        $this->dispatch('notify', message: 'Data karyawan telah dihapus.', type: 'success');
    }

    public function render()
    {
        // Ambil peran untuk dropdown
        $daftarOpsiPeran = Peran::all();

        $karyawan = User::whereNotNull('id_peran') // Asumsi karyawan adalah yang punya peran
            ->orWhereIn('role', ['admin', 'technician', 'cashier', 'warehouse', 'hr'])
            ->where(function($q) {
                $q->where('name', 'like', '%'.$this->cari.'%')
                  ->orWhere('email', 'like', '%'.$this->cari.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.employees.index', [
            'karyawan' => $karyawan,
            'daftarOpsiPeran' => $daftarOpsiPeran
        ]);
    }
}