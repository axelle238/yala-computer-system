<?php

namespace App\Livewire\News;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Berita - Admin Panel')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $categoryFilter = '';

    public function updatedSearch() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }
    public function updatedCategoryFilter() { $this->resetPage(); }

    public function delete($id)
    {
        $article = Article::find($id);
        if ($article) {
            $article->delete();
            $this->dispatch('notify', message: 'Berita berhasil dihapus.', type: 'success');
        }
    }

    public function render()
    {
        $articles = Article::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->statusFilter !== '', fn($q) => $q->where('is_published', $this->statusFilter))
            ->when($this->categoryFilter, fn($q) => $q->where('category', $this->categoryFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.news.index', [
            'articles' => $articles,
            'categories' => ['General', 'Teknologi', 'Tips & Trik', 'Promo', 'Hardware', 'Review']
        ]);
    }
}