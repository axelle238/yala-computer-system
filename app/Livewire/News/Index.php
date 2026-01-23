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

    public function updatedSearch()
    {
        $this->resetPage();
    }

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
        $articles = Article::where('title', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.news.index', [
            'articles' => $articles
        ]);
    }
}
