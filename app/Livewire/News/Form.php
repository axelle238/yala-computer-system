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
    public $category = 'General';
    public $tags = '';
    public $author_name;
    public $is_featured = false;
    public $is_published = true;

    public $categories = ['General', 'Teknologi', 'Tips & Trik', 'Promo', 'Hardware', 'Review'];

    public function mount($id = null)
    {
        if ($id) {
            $this->article = Article::findOrFail($id);
            $this->title = $this->article->title;
            $this->slug = $this->article->slug;
            $this->content = $this->article->content;
            $this->oldImage = $this->article->image_path;
            $this->category = $this->article->category;
            $this->tags = $this->article->tags ? implode(', ', $this->article->tags) : '';
            $this->author_name = $this->article->author_name;
            $this->is_featured = $this->article->is_featured;
            $this->is_published = $this->article->is_published;
        } else {
            $this->author_name = auth()->user()->name;
        }
    }

    public function updatedTitle()
    {
        if (!$this->article) {
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
            'category' => 'required|string',
            'author_name' => 'nullable|string',
        ]);

        $imagePath = $this->oldImage;
        if ($this->image) {
            $imagePath = $this->image->store('articles', 'public');
        }

        // Process tags
        $tagsArray = array_map('trim', explode(',', $this->tags));
        $tagsArray = array_filter($tagsArray);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'image_path' => $imagePath,
            'category' => $this->category,
            'tags' => $tagsArray,
            'author_name' => $this->author_name,
            'is_featured' => $this->is_featured,
            'is_published' => $this->is_published,
            'excerpt' => Str::limit(strip_tags($this->content), 150),
            'published_at' => $this->is_published ? ($this->article->published_at ?? now()) : null,
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