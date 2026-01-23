<?php

namespace App\Livewire\Store\News;

use App\Models\Article;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Session;

#[Layout('layouts.store')]
class Show extends Component
{
    public $article;

    public function mount($slug)
    {
        $this->article = Article::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Increment view count (unique per session to avoid spam)
        $viewKey = 'article_viewed_' . $this->article->id;
        if (!Session::has($viewKey)) {
            $this->article->increment('views_count');
            Session::put($viewKey, true);
        }
    }

    public function render()
    {
        $related = Article::where('id', '!=', $this->article->id)
            ->where('is_published', true)
            ->where('category', $this->article->category) // Prefer same category
            ->latest()
            ->take(3)
            ->get();

        if ($related->isEmpty()) {
             $related = Article::where('id', '!=', $this->article->id)
                ->where('is_published', true)
                ->latest()
                ->take(3)
                ->get();
        }

        return view('livewire.store.news.show', [
            'related' => $related
        ])->title($this->article->title . ' - Berita Yala Computer');
    }
}