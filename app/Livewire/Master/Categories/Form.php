<?php

namespace App\Livewire\Master\Categories;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Form Kategori - Yala Computer')]
class Form extends Component
{
    public $category_id;
    public $name;
    public $description;

    public function mount($id = null)
    {
        if ($id) {
            $category = Category::findOrFail($id);
            $this->category_id = $category->id;
            $this->name = $category->name;
            $this->description = $category->description;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::updateOrCreate(
            ['id' => $this->category_id],
            [
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description
            ]
        );

        $this->dispatch('notify', message: 'Kategori berhasil disimpan!', type: 'success');
        return redirect()->route('master.categories');
    }

    public function render()
    {
        return view('livewire.master.categories.form');
    }
}
