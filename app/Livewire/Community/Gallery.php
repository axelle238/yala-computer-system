<?php

namespace App\Livewire\Community;

use App\Models\SavedBuild;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('layouts.store')]
#[Title('Galeri Komunitas - Yala Computer')]
class Gallery extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $builds = SavedBuild::where('is_public', true)
            ->with('user')
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('description', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(9);

        return view('livewire.community.gallery', [
            'builds' => $builds
        ]);
    }
}