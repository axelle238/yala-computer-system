<?php

namespace App\Livewire\Store;

use App\Models\Faq; // Asumsi model FAQ
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Pusat Bantuan - Yala Computer')]
class HelpCenter extends Component
{
    public $search = '';

    public $activeCategory = null;

    public function selectCategory($id)
    {
        $this->activeCategory = $id;
    }

    public function render()
    {
        // Mocking Data if Models don't exist yet to prevent crash
        $categories = [
            ['id' => 1, 'name' => 'Pesanan & Pengiriman', 'icon' => 'truck'],
            ['id' => 2, 'name' => 'Garansi & Retur', 'icon' => 'shield-check'],
            ['id' => 3, 'name' => 'Rakit PC', 'icon' => 'cpu-chip'],
            ['id' => 4, 'name' => 'Akun & Keamanan', 'icon' => 'user-group'],
        ];

        $faqs = [
            ['category_id' => 1, 'question' => 'Berapa lama pengiriman ke luar kota?', 'answer' => 'Pengiriman reguler estimasi 2-4 hari kerja.'],
            ['category_id' => 1, 'question' => 'Bagaimana cara melacak pesanan?', 'answer' => 'Gunakan menu Lacak Pesanan di navbar atas.'],
            ['category_id' => 2, 'question' => 'Syarat klaim garansi?', 'answer' => 'Produk tidak cacat fisik dan segel utuh.'],
            ['category_id' => 3, 'question' => 'Apakah rakit PC gratis?', 'answer' => 'Ya, jasa rakit gratis untuk pembelian full set.'],
        ];

        // Filtering logic (Simple array filter for mock)
        $filteredFaqs = collect($faqs);

        if ($this->activeCategory) {
            $filteredFaqs = $filteredFaqs->where('category_id', $this->activeCategory);
        }

        if ($this->search) {
            $filteredFaqs = $filteredFaqs->filter(function ($faq) {
                return stripos($faq['question'], $this->search) !== false;
            });
        }

        return view('livewire.store.help-center', [
            'categories' => $categories,
            'faqs' => $filteredFaqs,
        ]);
    }
}
