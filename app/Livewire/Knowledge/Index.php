<?php

namespace App\Livewire\Knowledge;

use App\Models\KnowledgeArticle;
use App\Services\YalaIntelligence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Pusat Pengetahuan & SOP - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    // Filter
    public $cari = '';

    public $filterKategori = '';

    // Status Panel
    public $sedangMengedit = false;

    public $sedangMembaca = false;

    // Data Formulir
    public $idArtikel;

    public $judul;

    public $kategori;

    public $konten;

    // Data Tampilan
    public $artikelAktif;
    public $ringkasanAi = '';

    /**
     * Membuka panel buat artikel baru.
     */
    public function buat()
    {
        $this->reset(['judul', 'kategori', 'konten', 'idArtikel']);
        $this->sedangMengedit = true;
        $this->sedangMembaca = false;
    }

    /**
     * Membuka panel edit artikel.
     */
    public function ubah($id)
    {
        $artikel = KnowledgeArticle::findOrFail($id);
        $this->idArtikel = $id;
        $this->judul = $artikel->title;
        $this->kategori = $artikel->category;
        $this->konten = $artikel->content;

        $this->sedangMengedit = true;
        $this->sedangMembaca = false;
    }

    /**
     * Membuka panel baca artikel.
     */
    public function baca($id, YalaIntelligence $ai)
    {
        $this->artikelAktif = KnowledgeArticle::with('penulis')->findOrFail($id);
        $this->ringkasanAi = $ai->ringkasArtikel($this->artikelAktif->content);
        $this->sedangMembaca = true;
        $this->sedangMengedit = false;
    }

    /**
     * Menyimpan artikel ke database.
     */
    public function simpan()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'konten' => 'required|string',
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'konten.required' => 'Isi konten tidak boleh kosong.',
        ]);

        $data = [
            'title' => $this->judul,
            'slug' => Str::slug($this->judul).'-'.rand(100, 999),
            'category' => $this->kategori,
            'content' => $this->konten,
            'author_id' => Auth::id(),
        ];

        if ($this->idArtikel) {
            KnowledgeArticle::find($this->idArtikel)->update($data);
            $this->dispatch('notify', message: 'Artikel berhasil diperbarui.', type: 'success');
        } else {
            KnowledgeArticle::create($data);
            $this->dispatch('notify', message: 'Artikel baru berhasil diterbitkan.', type: 'success');
        }

        $this->sedangMengedit = false;
    }

    /**
     * Menghapus artikel.
     */
    public function hapus($id)
    {
        KnowledgeArticle::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Artikel berhasil dihapus dari sistem.', type: 'success');
        $this->sedangMembaca = false;
    }

    /**
     * Menutup semua panel.
     */
    public function tutupPanel()
    {
        $this->sedangMengedit = false;
        $this->sedangMembaca = false;
        $this->reset(['idArtikel', 'judul', 'kategori', 'konten']);
    }

    public function render()
    {
        $daftarKategori = KnowledgeArticle::select('category')->distinct()->pluck('category');

        $daftarArtikel = KnowledgeArticle::with('penulis')
            ->when($this->cari, fn ($q) => $q->where('title', 'like', '%'.$this->cari.'%'))
            ->when($this->filterKategori, fn ($q) => $q->where('category', $this->filterKategori))
            ->latest()
            ->paginate(12);

        return view('livewire.knowledge.index', [
            'daftarArtikel' => $daftarArtikel,
            'daftarKategori' => $daftarKategori,
        ]);
    }
}
