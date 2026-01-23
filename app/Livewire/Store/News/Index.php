<?php

namespace App\Livewire\Store\News;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Layout('layouts.store')]
#[Title('Berita & Artikel Terbaru - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $category = '';

    public function filterCategory($category)
    {
        $this->category = $category === $this->category ? '' : $category;
        $this->resetPage();
    }

    public function render()
    {
        $articles = Article::where('is_published', true)
            ->when($this->category, function ($query) {
                $query->where('category', $this->category);
            })
            ->latest()
            ->paginate(9);

        // Get unique categories that actually have published articles
        $categories = Article::where('is_published', true)
            ->distinct()
            ->pluck('category')
            ->sort();

        return view('livewire.store.news.index', [
            'articles' => $articles,
            'categories' => $categories
        ]);
    }
}