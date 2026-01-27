<?php

namespace App\Livewire\News;

use App\Models\Article;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen Berita & Artikel')]
class Index extends Component
{
    use WithPagination;

    public $cari = '';

    public function delete($id)
    {
        Article::find($id)->delete();
        $this->dispatch('notify', message: 'Artikel berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $articles = Article::where('title', 'like', '%'.$this->cari.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.news.index', ['articles' => $articles]);
    }
}
