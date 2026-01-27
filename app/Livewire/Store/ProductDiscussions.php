<?php

namespace App\Livewire\Store;

use App\Models\ProductDiscussion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProductDiscussions extends Component
{
    use WithPagination;

    public $productId;

    public $pesanBaru;

    public $idBalasan = null; // ID diskusi yang sedang dibalas

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function kirimPesan()
    {
        if (! Auth::check()) {
            return redirect()->route('pelanggan.masuk');
        }

        $this->validate([
            'pesanBaru' => 'required|string|min:3|max:500',
        ], [
            'pesanBaru.required' => 'Tuliskan pertanyaan atau komentar Anda.',
            'pesanBaru.min' => 'Pesan terlalu pendek.',
        ]);

        ProductDiscussion::create([
            'product_id' => $this->productId,
            'user_id' => Auth::id(),
            'message' => $this->pesanBaru,
            'parent_id' => $this->idBalasan,
            'is_admin_reply' => false, // Default user
        ]);

        $this->pesanBaru = '';
        $this->idBalasan = null;
        $this->dispatch('notify', message: 'Diskusi berhasil dikirim.', type: 'success');
    }

    public function setBalas($id)
    {
        $this->idBalasan = $id;
    }

    public function batalBalas()
    {
        $this->idBalasan = null;
    }

    public function render()
    {
        $diskusi = ProductDiscussion::with(['user', 'replies.user'])
            ->where('product_id', $this->productId)
            ->whereNull('parent_id') // Hanya ambil pesan utama
            ->latest()
            ->paginate(5);

        return view('livewire.store.product-discussions', [
            'diskusi' => $diskusi,
        ]);
    }
}