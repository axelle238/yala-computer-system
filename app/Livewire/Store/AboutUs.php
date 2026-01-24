<?php

namespace App\Livewire\Store;

use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Tentang Kami - Yala Computer')]
class AboutUs extends Component
{
    public function render()
    {
        return view('livewire.store.about-us', [
            'storeName' => Setting::get('store_name', 'Yala Computer'),
            'description' => Setting::get('footer_description', ''),
        ]);
    }
}