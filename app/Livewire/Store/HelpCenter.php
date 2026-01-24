<?php

namespace App\Livewire\Store;

use App\Models\FaqCategory;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Pusat Bantuan - Yala Computer')]
class HelpCenter extends Component
{
    public $activeCategory = null;
    public $search = '';

    public function render()
    {
        $categories = FaqCategory::where('is_active', true)
            ->with(['faqs' => function($q) {
                $q->where('is_published', true)
                  ->when($this->search, function($sq) {
                      $sq->where('question', 'like', '%'.$this->search.'%')
                         ->orWhere('answer', 'like', '%'.$this->search.'%');
                  });
            }])
            ->orderBy('order_index')
            ->get();

        return view('livewire.store.help-center', [
            'categories' => $categories
        ]);
    }
}