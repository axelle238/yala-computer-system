<?php

namespace App\Livewire\Pemasaran\Ulasan;

use App\Models\Review;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Pengelola Ulasan - Yala Computer')]
class Pengelola extends Component
{
    use WithPagination;

    public function toggleApproval($id)
    {
        $review = Review::find($id);
        $review->update(['is_approved' => ! $review->is_approved]);
        $this->dispatch('notify', message: 'Status ulasan diperbarui.', type: 'success');
    }

    public function render()
    {
        $reviews = Review::with(['user', 'product'])->latest()->paginate(10);

        return view('livewire.pemasaran.ulasan.pengelola', ['reviews' => $reviews]);
    }
}
