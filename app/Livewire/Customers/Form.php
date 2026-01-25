<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Formulir Pelanggan - Yala Computer')]
class Form extends Component
{
    /**
     * Properti Formulir Pelanggan.
     */
    public $idPelanggan;
    public $nama;
    public $telepon;
    public $surel;

    public function mount($id = null)
    {
        if ($id) {
            $pelanggan = Customer::findOrFail($id);
            $this->idPelanggan = $pelanggan->id;
            $this->nama = $pelanggan->name;
            $this->telepon = $pelanggan->phone;
            $this->surel = $pelanggan->email;
        }
    }

    /**
     * Menyimpan data pelanggan ke database.
     */
    public function simpan()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20|unique:customers,phone,' . $this->idPelanggan,
            'surel' => 'nullable|email|max:255',
        ], [
            'nama.required' => 'Nama pelanggan wajib diisi.',
            'telepon.required' => 'Nomor telepon wajib diisi.',
            'telepon.unique' => 'Nomor telepon sudah terdaftar.',
            'surel.email' => 'Format surel tidak valid.',
        ]);

        Customer::updateOrCreate(
            ['id' => $this->idPelanggan],
            [
                'name' => $this->nama,
                'phone' => $this->telepon,
                'email' => $this->surel,
                'join_date' => $this->idPelanggan ? Customer::find($this->idPelanggan)->join_date : now(),
            ]
        );

        $this->dispatch('notify', message: 'Data pelanggan berhasil disimpan!', type: 'success');
        
        return redirect()->route('customers.index');
    }

    public function render()
    {
        return view('livewire.customers.form');
    }
}