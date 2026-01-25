<?php

namespace App\Livewire\Store;

use App\Models\PesanPelanggan;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Hubungi Kami - Yala Computer')]
class ContactUs extends Component
{
    /**
     * Properti formulir dalam Bahasa Indonesia.
     */
    public $nama;

    public $surel;

    public $subjek;

    public $pesan;

    /**
     * Mengirim pesan pelanggan ke database.
     */
    public function kirimPesan()
    {
        $this->validate([
            'nama' => 'required|min:3',
            'surel' => 'required|email',
            'subjek' => 'required|min:5',
            'pesan' => 'required|min:10',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.min' => 'Nama minimal 3 karakter.',
            'surel.required' => 'Surel wajib diisi.',
            'surel.email' => 'Format surel tidak valid.',
            'subjek.required' => 'Subjek wajib diisi.',
            'subjek.min' => 'Subjek minimal 5 karakter.',
            'pesan.required' => 'Pesan wajib diisi.',
            'pesan.min' => 'Isi pesan minimal 10 karakter.',
        ]);

        PesanPelanggan::create([
            'nama' => $this->nama,
            'surel' => $this->surel,
            'subjek' => $this->subjek,
            'isi_pesan' => $this->pesan,
            'status' => PesanPelanggan::STATUS_BARU,
        ]);

        $this->reset(['nama', 'surel', 'subjek', 'pesan']);

        $this->dispatch('notify',
            message: 'Pesan berhasil terkirim! Tim kami akan segera menghubungi Anda melalui surel.',
            type: 'success'
        );
    }

    public function render()
    {
        return view('livewire.store.contact-us');
    }
}
