<?php

namespace App\Livewire\Community;

use App\Models\SavedBuild;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.store')]
#[Title('Galeri Komunitas Rakit PC - Yala Computer')]
class Gallery extends Component
{
    use WithPagination;

    public $search = '';

    public $sort = 'latest'; // latest, popular

    public function toggleLike($buildId)
    {
        if (! auth()->check()) {
            return redirect()->route('pelanggan.masuk');
        }

        $build = SavedBuild::findOrFail($buildId);
        $like = \App\Models\BuildLike::where('user_id', auth()->id())
            ->where('saved_build_id', $buildId)
            ->first();

        if ($like) {
            $like->delete();
            $build->decrement('likes_count');
        } else {
            \App\Models\BuildLike::create([
                'user_id' => auth()->id(),
                'saved_build_id' => $buildId,
            ]);
            $build->increment('likes_count');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = SavedBuild::query()
            ->where('is_public', true)
            ->with(['user'])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });

        if ($this->sort === 'popular') {
            $query->orderByDesc('likes_count');
        } else {
            $query->latest();
        }

        return view('livewire.community.gallery', [
            'builds' => $query->paginate(12),
        ]);
    }
}