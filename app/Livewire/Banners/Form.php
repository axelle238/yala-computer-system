<?php

namespace App\Livewire\Banners;

use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Form Banner - Yala Computer')]
class Form extends Component
{
    use WithFileUploads;

    public ?Banner $banner = null;

    public $title;

    public $description;

    public $button_text; // New Field

    public $image; // Temporary upload

    public $existingImage;

    public $link_url;

    public $is_active = true;

    public $order = 0;

    public function mount($id = null)
    {
        if ($id) {
            $this->banner = Banner::findOrFail($id);
            $this->title = $this->banner->title;
            $this->description = $this->banner->description;
            $this->button_text = $this->banner->button_text; // New Field
            $this->existingImage = $this->banner->image_path;
            $this->link_url = $this->banner->link_url;
            $this->is_active = $this->banner->is_active;
            $this->order = $this->banner->order;
        } else {
            $this->order = Banner::max('order') + 1;
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'image' => $this->banner ? 'nullable|image|max:2048' : 'required|image|max:2048',
            'link_url' => 'nullable|url',
            'order' => 'integer',
        ]);

        $imagePath = $this->existingImage;
        if ($this->image) {
            if ($this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $imagePath = $this->image->store('banners', 'public');
        }

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'button_text' => $this->button_text, // New Field
            'image_path' => $imagePath,
            'link_url' => $this->link_url,
            'is_active' => $this->is_active,
            'order' => $this->order,
        ];

        if ($this->banner) {
            $this->banner->update($data);
            session()->flash('success', 'Banner diperbarui.');
        } else {
            Banner::create($data);
            session()->flash('success', 'Banner baru ditambahkan.');
        }

        return redirect()->route('admin.spanduk.indeks');
    }

    public function render()
    {
        return view('livewire.banners.form');
    }
}
