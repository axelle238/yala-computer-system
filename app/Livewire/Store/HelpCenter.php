<?php

namespace App\Livewire\Store;

use App\Models\KnowledgeArticle;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Pusat Bantuan - Yala Computer')]
class HelpCenter extends Component
{
    public $cari = '';

    public $kategoriAktif = null;

    public function pilihKategori($kategori)
    {
        $this->kategoriAktif = $kategori === $this->kategoriAktif ? null : $kategori;
    }

    public function render()
    {
        // Mengambil kategori unik yang ada di database
        $kategoriList = KnowledgeArticle::select('category')
            ->distinct()
            ->pluck('category')
            ->map(function ($cat) {
                // Ikon Mapping sederhana berdasarkan nama kategori
                $icon = match (strtolower($cat)) {
                    'pengiriman', 'logistik' => 'truck',
                    'garansi', 'retur' => 'shield-check',
                    'teknis', 'rakit pc' => 'cpu-chip',
                    'akun', 'keamanan' => 'user-group',
                    default => 'book-open'
                };

                return ['name' => $cat, 'icon' => $icon];
            });

        $artikel = KnowledgeArticle::query()
            ->when($this->kategoriAktif, fn ($q) => $q->where('category', $this->kategoriAktif))
            ->when($this->cari, fn ($q) => $q->where('title', 'like', '%'.$this->cari.'%')->orWhere('content', 'like', '%'.$this->cari.'%'))
            ->get();

        return view('livewire.store.help-center', [
            'daftarKategori' => $kategoriList,
            'daftarArtikel' => $artikel,
        ]);
    }
}
