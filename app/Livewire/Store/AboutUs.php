<?php

namespace App\Livewire\Store;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Tentang Kami - Yala Computer')]
class AboutUs extends Component
{
    public function render()
    {
        return view('livewire.store.about-us');
    }
}
