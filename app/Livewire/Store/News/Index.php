<?php

namespace App\Livewire\Store\News;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Berita & Artikel - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $category = '';

    public function filter($cat)
    {
        $this->category = $cat;
        $this->resetPage();
    }

    public function render()
    {
        $articles = Article::where('is_published', true)
            ->when($this->category, fn($q) => $q->where('category', $this->category))
            ->latest('published_at')
            ->paginate(9);

        $featured = Article::where('is_published', true)->latest('published_at')->first();

        return view('livewire.store.news.index', [
            'articles' => $articles,
            'featured' => $featured
        ]);
    }
}