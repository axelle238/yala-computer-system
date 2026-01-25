<?php

namespace App\Livewire\Store;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Tentang Kami - Yala Computer')]
class AboutUs extends Component
{
    public function render()
    {
        // Mock Team Data (In real app, this could be from DB)
        $teams = [
            ['name' => 'Axelle Bazyli', 'role' => 'Founder & CEO', 'image' => 'https://ui-avatars.com/api/?name=Axelle+Bazyli&background=0D8ABC&color=fff'],
            ['name' => 'Sarah Tech', 'role' => 'Lead Technician', 'image' => 'https://ui-avatars.com/api/?name=Sarah+Tech&background=6D28D9&color=fff'],
            ['name' => 'John Sales', 'role' => 'Sales Manager', 'image' => 'https://ui-avatars.com/api/?name=John+Sales&background=059669&color=fff'],
        ];

        return view('livewire.store.about-us', ['teams' => $teams]);
    }
}
