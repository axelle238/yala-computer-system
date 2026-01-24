<?php

namespace App\Livewire\Store\News;

use App\Models\Article;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('layouts.store')]
#[Title('Berita & Artikel Teknologi - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $articles = Article::where('is_published', true)
            ->where(function($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                  ->orWhere('content', 'like', '%'.$this->search.'%');
            })
            ->with('author')
            ->latest('published_at')
            ->paginate(9);

        $featured = Article::where('is_published', true)->latest('views_count')->first();

        return view('livewire.store.news.index', [
            'articles' => $articles,
            'featured' => $featured
        ]);
    }
}
