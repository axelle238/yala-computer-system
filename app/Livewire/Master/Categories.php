<?php

namespace App\Livewire\Master;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Master Kategori - Yala Computer')]
class Categories extends Component
{
    use WithPagination;

    public $search = '';
    public $name, $description, $category_id;
    public $isModalOpen = false;

    public function render()
    {
        $categories = Category::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.master.categories', ['categories' => $categories]);
    }

    public function create()
    {
        $this->reset(['name', 'description', 'category_id']);
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->isModalOpen = true;
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
        $this->isModalOpen = false;
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Kategori dihapus.', type: 'info');
    }
}
