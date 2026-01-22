<?php

namespace App\Livewire\Banners;

use App\Models\Banner;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Form Banner - Yala Computer')]
class Form extends Component
{
    use WithFileUploads;

    public ?Banner $banner = null;

    public $title = '';
    public $link_url = '';
    public $image;
    public $image_path;
    public $order = 0;
    public $is_active = true;

    public function mount($id = null)
    {
        if ($id) {
            $this->banner = Banner::findOrFail($id);
            $this->title = $this->banner->title;
            $this->link_url = $this->banner->link_url;
            $this->image_path = $this->banner->image_path;
            $this->order = $this->banner->order;
            $this->is_active = $this->banner->is_active;
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

        $data = [
            'title' => $this->title,
            'link_url' => $this->link_url,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('banners', 'public');
        }

        if ($this->banner) {
            $this->banner->update($data);
        } else {
            Banner::create($data);
        }

        session()->flash('success', 'Banner berhasil disimpan.');
        return redirect()->route('banners.index');
    }

    public function render()
    {
        return view('livewire.banners.form');
    }
}
