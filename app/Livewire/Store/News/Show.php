<?php

namespace App\Livewire\Store\News;

use App\Models\Article;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
class Show extends Component
{
    public $article;

    public function mount($slug)
    {
        $this->article = Article::where('slug', $slug)->where('is_published', true)->firstOrFail();
    }

    #[Title]
    public function pageTitle()
    {
        return $this->article->title.' - Yala Computer';
    }

    public function render()
    {
        $related = Article::where('is_published', true)
            ->where('category', $this->article->category)
            ->where('id', '!=', $this->article->id)
            ->take(3)
            ->get();

        return view('livewire.store.news.show', ['related' => $related]);
    }
}
