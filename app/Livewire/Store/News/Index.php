<?php

namespace App\Livewire\Store\News;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Berita & Artikel Terbaru - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $articles = Article::where('is_published', true)
            ->latest()
            ->paginate(9);

        return view('livewire.store.news.index', [
            'articles' => $articles
        ]);
    }
}
