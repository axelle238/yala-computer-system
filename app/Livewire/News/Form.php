<?php

namespace App\Livewire\News;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
#[Title('Form Berita - Admin Panel')]
class Form extends Component
{
    use WithFileUploads;

    public $article;
    public $title;
    public $slug;
    public $content;
    public $image;
    public $oldImage;
    public $is_published = true;

    public function mount($id = null)
    {
        if ($id) {
            $this->article = Article::findOrFail($id);
            $this->title = $this->article->title;
            $this->slug = $this->article->slug;
            $this->content = $this->article->content;
            $this->oldImage = $this->article->image_path;
            $this->is_published = $this->article->is_published;
        }
    }

    public function updatedTitle()
    {
        if (!$this->article) { // Only auto-generate slug on create
            $this->slug = Str::slug($this->title);
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug,' . ($this->article->id ?? 'NULL'),
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $this->oldImage;
        if ($this->image) {
            $imagePath = $this->image->store('articles', 'public');
        }

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'image_path' => $imagePath,
            'is_published' => $this->is_published,
            'excerpt' => Str::limit(strip_tags($this->content), 150),
            'published_at' => $this->is_published ? now() : null,
        ];

        if ($this->article) {
            $this->article->update($data);
            $this->dispatch('notify', message: 'Berita diperbarui!', type: 'success');
        } else {
            Article::create($data);
            $this->dispatch('notify', message: 'Berita dibuat!', type: 'success');
        }

        return redirect()->route('admin.news.index');
    }

    public function render()
    {
        return view('livewire.news.form');
    }
}
