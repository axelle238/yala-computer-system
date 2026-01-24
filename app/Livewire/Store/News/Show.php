<?php

namespace App\Livewire\Store\News;

use App\Models\Article;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
class Show extends Component
{
    public Article $article;

    public function mount($slug)
    {
        $this->article = Article::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
            
        $this->article->increment('views_count');
    }

    #[Title]
    public function pageTitle()
    {
        return $this->article->title . ' - Yala News';
    }

    public function render()
    {
        return view('livewire.store.news.show');
    }
}
