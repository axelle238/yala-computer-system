<?php

namespace App\Livewire\Master;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Master Kategori - Yala Computer')]
class Categories extends Component
{
    use WithPagination;

    public $cari = '';

    public function render()
    {
        $categories = Category::where('name', 'like', '%'.$this->cari.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.master.categories', ['categories' => $categories]);
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Kategori dihapus.', type: 'info');
    }
}
