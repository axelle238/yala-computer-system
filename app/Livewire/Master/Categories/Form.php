<?php

namespace App\Livewire\Master\Categories;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Formulir Kategori - Yala Computer')]
class Form extends Component
{
    /**
     * Properti Formulir Kategori.
     */
    public $idKategori;

    public $nama;

    public $deskripsi;

    public function mount($id = null)
    {
        if ($id) {
            $kategori = Category::findOrFail($id);
            $this->idKategori = $kategori->id;
            $this->nama = $kategori->name;
            $this->deskripsi = $kategori->description;
        }
    }

    /**
     * Menyimpan data kategori ke database.
     */
    public function simpan()
    {
        $this->validate([
            'nama' => 'required|string|max:255|unique:categories,name,'.$this->idKategori,
            'deskripsi' => 'nullable|string',
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Nama kategori sudah ada.',
        ]);

        Category::updateOrCreate(
            ['id' => $this->idKategori],
            [
                'name' => $this->nama,
                'slug' => Str::slug($this->nama),
                'description' => $this->deskripsi,
            ]
        );

        $this->dispatch('notify', message: 'Kategori berhasil disimpan!', type: 'success');

        return redirect()->route('master.categories');
    }

    public function render()
    {
        return view('livewire.master.categories.form');
    }
}
