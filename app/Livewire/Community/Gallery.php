<?php

namespace App\Livewire\Community;

use App\Models\BuildLike;
use App\Models\SavedBuild;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Galeri Komunitas - Yala Computer')]
class Gallery extends Component
{
    use WithPagination;

    public $search = '';
    public $sort = 'popular'; // latest, popular, oldest

    public function toggleLike($buildId)
    {
        if (!Auth::check()) {
            $this->dispatch('notify', message: 'Silakan login untuk menyukai rakitan.', type: 'error');
            return;
        }

        $userId = Auth::id();
        $existing = BuildLike::where('user_id', $userId)->where('saved_build_id', $buildId)->first();

        if ($existing) {
            $existing->delete();
            SavedBuild::where('id', $buildId)->decrement('likes_count');
        } else {
            BuildLike::create(['user_id' => $userId, 'saved_build_id' => $buildId]);
            SavedBuild::where('id', $buildId)->increment('likes_count');
        }
    }

    public function cloneBuild($buildId)
    {
        $build = SavedBuild::findOrFail($buildId);
        
        // Transform SavedBuild format to PcBuilder session format
        // SavedBuild: ['processors' => ['id' => 1, ...], ...]
        // PcBuilder Session: selection = ['processors' => ['id' => 1, ...]] (Essentially same structure in current PcBuilder implementation)
        
        // We need to inject this into the PcBuilder Component instance or just pass query param?
        // Since PcBuilder uses public property `selection`, passing it via URL/Session is tricky if complex.
        // Simplest complex way: Create a new (private) SavedBuild for the current user based on this one, then redirect to builder loading that ID.
        // OR: Just set session variable 'cloned_build' and let PcBuilder mount check it.
        
        session()->put('cloned_build', $build->components);
        session()->put('cloned_build_name', 'Copy of ' . $build->name);
        
        $this->dispatch('notify', message: 'Konfigurasi berhasil disalin! Mengalihkan ke Builder...', type: 'success');
        return redirect()->route('pc-builder');
    }

    public function render()
    {
        $builds = SavedBuild::with('user')
            ->where('is_public', true)
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->sort === 'popular', fn($q) => $q->orderByDesc('likes_count'))
            ->when($this->sort === 'latest', fn($q) => $q->latest())
            ->when($this->sort === 'oldest', fn($q) => $q->oldest())
            ->paginate(9);

        return view('livewire.community.gallery', [
            'builds' => $builds
        ]);
    }
}
