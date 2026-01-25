<?php

namespace App\Livewire\Member;

use App\Models\Quotation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.store')]
#[Title('Penawaran Saya (B2B) - Yala Computer')]
class Quotations extends Component
{
    use WithPagination;

    public function render()
    {
        $quotations = Quotation::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.member.quotations', [
            'quotations' => $quotations,
        ]);
    }
}
