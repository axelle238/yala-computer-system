<?php

namespace App\Livewire\Banners;

use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Manajemen Banner - Yala Computer')]
class Index extends Component
{
    public function delete($id)
    {
        $banner = Banner::findOrFail($id);
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }
        $banner->delete();
        $this->dispatch('notify', message: 'Banner berhasil dihapus.', type: 'success');
    }

    public function toggleActive($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->update(['is_active' => ! $banner->is_active]);
    }

    public function render()
    {
        return view('livewire.banners.index', [
            'banners' => Banner::orderBy('order')->latest()->get(),
        ]);
    }
}
