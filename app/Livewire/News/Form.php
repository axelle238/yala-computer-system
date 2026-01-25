<?php

namespace App\Livewire\News;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Editor Artikel')]
class Form extends Component
{
    use WithFileUploads;

    public $articleId;

    public $title;

    public $slug;

    public $content;

    public $category = 'news'; // news, tutorial, promo

    public $is_published = true;

    public $image;

    public $existingImage;

    public function mount($id = null)
    {
        if ($id) {
            $article = Article::findOrFail($id);
            $this->articleId = $article->id;
            $this->title = $article->title;
            $this->slug = $article->slug;
            $this->content = $article->content;
            $this->category = $article->category;
            $this->is_published = $article->is_published;
            $this->existingImage = $article->image_path;
        }
    }

    public function updatedTitle()
    {
        $this->slug = Str::slug($this->title);
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|min:5',
            'slug' => 'required|unique:articles,slug,'.$this->articleId,
            'content' => 'required|min:20',
            'category' => 'required',
            'image' => $this->articleId ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ]);

        $imagePath = $this->existingImage;
        if ($this->image) {
            $imagePath = $this->image->store('articles', 'public');
        }

        Article::updateOrCreate(['id' => $this->articleId], [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'category' => $this->category,
            'is_published' => $this->is_published,
            'image_path' => $imagePath,
            'user_id' => Auth::id(),
            'published_at' => $this->is_published ? now() : null,
        ]);

        session()->flash('success', 'Artikel berhasil disimpan.');

        return redirect()->route('news.index');
    }

    public function render()
    {
        return view('livewire.news.form');
    }
}
