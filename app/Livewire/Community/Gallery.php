<?php

namespace App\Livewire\Community;

use App\Models\BuildLike; // Asumsi model
use App\Models\BuildComment; // Asumsi model
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
    
    // Upload Form (Simplified: Using SavedBuild as source)
    public $showUploadModal = false;
    public $selectedBuildId;
    public $galleryTitle;
    public $galleryDesc;

    public function mount()
    {
        // Mock data logic or real DB logic if migration exists
    }

    public function openUploadModal()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $this->reset(['selectedBuildId', 'galleryTitle', 'galleryDesc']);
        $this->showUploadModal = true;
    }

    public function publishBuild()
    {
        $this->validate([
            'selectedBuildId' => 'required',
            'galleryTitle' => 'required|min:5',
            'galleryDesc' => 'required|min:10',
        ]);

        // Logic: Update SavedBuild to be 'public'/published
        // SavedBuild::where('id', $this->selectedBuildId)->update([...]);
        
        // Mock success
        $this->showUploadModal = false;
        $this->dispatch('notify', message: 'Rakitan berhasil dipublikasikan ke galeri!', type: 'success');
    }

    public function like($buildId)
    {
        if (!Auth::check()) return redirect()->route('login');
        
        // BuildLike::create(...) logic
        $this->dispatch('notify', message: 'Anda menyukai rakitan ini.', type: 'success');
    }

    public function render()
    {
        // Get Published Builds (Mocking using SavedBuilds for demo)
        $builds = SavedBuild::with('user')
            ->where('name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(9);

        // User's private builds for selection
        $myBuilds = Auth::check() ? SavedBuild::where('user_id', Auth::id())->get() : [];

        return view('livewire.community.gallery', [
            'builds' => $builds,
            'myBuilds' => $myBuilds
        ]);
    }
}
