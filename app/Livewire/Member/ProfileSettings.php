<?php

namespace App\Livewire\Member;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Pengaturan Profil - Yala Computer')]
class ProfileSettings extends Component
{
    use WithFileUploads;

    /**
     * Properti Data Profil
     */
    public $nama;
    public $surel;
    public $telepon;
    public $nomorKtp;
    public $alamatLengkap;
    public $fotoProfil;
    public $pathFotoSaatIni;

    /**
     * Properti Keamanan (Kata Sandi)
     */
    public $kataSandiSaatIni;
    public $kataSandiBaru;
    public $konfirmasiKataSandi;

    public function mount()
    {
        $pengguna = Auth::user();
        $this->nama = $pengguna->name;
        $this->surel = $pengguna->email;
        $this->telepon = $pengguna->phone;
        $this->nomorKtp = $pengguna->nomor_ktp;
        $this->alamatLengkap = $pengguna->alamat_lengkap;
        $this->pathFotoSaatIni = $pengguna->path_foto_profil;
    }

    /**
     * Memperbarui informasi profil pengguna.
     */
    public function perbaruiProfil()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'nomorKtp' => 'nullable|digits:16',
            'alamatLengkap' => 'nullable|string|max:500',
            'fotoProfil' => 'nullable|image|max:2048', // Maksimal 2MB
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nomorKtp.digits' => 'Nomor KTP harus 16 digit.',
            'fotoProfil.image' => 'File harus berupa gambar.',
            'fotoProfil.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $pengguna = Auth::user();
        
        $data = [
            'name' => $this->nama,
            'phone' => $this->telepon,
            'nomor_ktp' => $this->nomorKtp,
            'alamat_lengkap' => $this->alamatLengkap,
        ];

        if ($this->fotoProfil) {
            $path = $this->fotoProfil->store('foto-profil', 'public');
            $data['path_foto_profil'] = $path;
            $this->pathFotoSaatIni = $path;
        }

        $pengguna->update($data);

        $this->dispatch('notify', message: 'Profil Anda berhasil diperbarui!', type: 'success');
    }

    /**
     * Memperbarui kata sandi pengguna.
     */
    public function perbaruiKataSandi()
    {
        $this->validate([
            'kataSandiSaatIni' => 'required|current_password',
            'kataSandiBaru' => 'required|min:8|different:kataSandiSaatIni',
            'konfirmasiKataSandi' => 'required|same:kataSandiBaru',
        ], [
            'kataSandiSaatIni.required' => 'Kata sandi saat ini wajib diisi.',
            'kataSandiSaatIni.current_password' => 'Kata sandi saat ini salah.',
            'kataSandiBaru.required' => 'Kata sandi baru wajib diisi.',
            'kataSandiBaru.min' => 'Kata sandi baru minimal 8 karakter.',
            'kataSandiBaru.different' => 'Kata sandi baru harus berbeda dengan kata sandi lama.',
            'konfirmasiKataSandi.required' => 'Konfirmasi kata sandi wajib diisi.',
            'konfirmasiKataSandi.same' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $pengguna = Auth::user();
        $pengguna->update([
            'password' => Hash::make($this->kataSandiBaru),
        ]);

        $this->reset(['kataSandiSaatIni', 'kataSandiBaru', 'konfirmasiKataSandi']);
        $this->dispatch('notify', message: 'Kata sandi berhasil diubah.', type: 'success');
    }

    public function render()
    {
        return view('livewire.member.profile-settings');
    }
}