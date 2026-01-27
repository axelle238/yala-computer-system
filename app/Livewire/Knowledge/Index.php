<?php

namespace App\Livewire\Knowledge;

use App\Models\KnowledgeArticle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Pusat Pengetahuan & SOP - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $cari = '';

    public $categoryFilter = '';

    // Editor State
    public $isEditing = false;

    public $isReading = false;

    public $articleId;

    public $title;

    public $category;

    public $content;

    public $activeArticle;

    public function create()
    {
        $this->reset(['title', 'category', 'content', 'articleId']);
        $this->isEditing = true;
        $this->isReading = false;
    }

    public function edit($id)
    {
        $article = KnowledgeArticle::findOrFail($id);
        $this->articleId = $id;
        $this->title = $article->title;
        $this->category = $article->category;
        $this->content = $article->content;
        $this->isEditing = true;
        $this->isReading = false;
    }

    public function read($id)
    {
        $this->activeArticle = KnowledgeArticle::with('penulis')->findOrFail($id);
        $this->isReading = true;
        $this->isEditing = false;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->title).'-'.rand(100, 999),
            'category' => $this->category,
            'content' => $this->content,
            'author_id' => Auth::id(),
        ];

        if ($this->articleId) {
            KnowledgeArticle::find($this->articleId)->update($data);
            $this->dispatch('notify', message: 'Artikel diperbarui.', type: 'success');
        } else {
            KnowledgeArticle::create($data);
            $this->dispatch('notify', message: 'Artikel baru diterbitkan.', type: 'success');
        }

        $this->isEditing = false;
    }

    public function delete($id)
    {
        KnowledgeArticle::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Artikel dihapus.', type: 'success');
        $this->isReading = false;
    }

    public function render()
    {
        $categories = KnowledgeArticle::select('category')->distinct()->pluck('category');

        $articles = KnowledgeArticle::with('penulis')
            ->when($this->cari, fn ($q) => $q->where('title', 'like', '%'.$this->cari.'%'))
            ->when($this->categoryFilter, fn ($q) => $q->where('category', $this->categoryFilter))
            ->latest()
            ->paginate(12);

        return view('livewire.knowledge.index', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }
}
