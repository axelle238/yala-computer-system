<?php

namespace App\Livewire\News;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen Berita & Artikel')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete($id)
    {
        Article::find($id)->delete();
        $this->dispatch('notify', message: 'Artikel berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $articles = Article::where('title', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.news.index', ['articles' => $articles]);
    }
}
