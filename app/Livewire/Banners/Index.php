<?php

namespace App\Livewire\Banners;

use App\Models\Banner;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Banner - Yala Computer')]
class Index extends Component
{
    public function delete($id)
    {
        Banner::findOrFail($id)->delete();
        session()->flash('success', 'Banner berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->update(['is_active' => !$banner->is_active]);
    }

    public function render()
    {
        return view('livewire.banners.index', [
            'banners' => Banner::orderBy('order')->latest()->get()
        ]);
    }
}
