<?php

namespace App\Livewire\Store\News;

use App\Models\Article;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
class Show extends Component
{
    public $article;

    public function mount($slug)
    {
        $this->article = Article::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
    }

    public function render()
    {
        $related = Article::where('id', '!=', $this->article->id)
            ->where('is_published', true)
            ->latest()
            ->take(3)
            ->get();

        return view('livewire.store.news.show', [
            'related' => $related
        ])->title($this->article->title . ' - Berita Yala Computer');
    }
}
