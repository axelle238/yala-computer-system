<?php

namespace App\Livewire\Assets;

use App\Models\CompanyAsset;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Formulir Aset - Yala Computer')]
class Form extends Component
{
    public $name;

    public $asset_tag;

    public $serial_number;

    public $purchase_price;

    public $purchase_date;

    public $useful_life_years = 4;

    public $location;

    public $condition = 'good';

    public function mount()
    {
        // Auto-generate Tag
        $count = CompanyAsset::count() + 1;
        $this->asset_tag = 'AST-'.date('Y').'-'.str_pad($count, 3, '0', STR_PAD_LEFT);
        $this->purchase_date = date('Y-m-d');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string',
            'asset_tag' => 'required|unique:company_assets,asset_tag',
            'purchase_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'useful_life_years' => 'required|integer|min:1',
        ]);

        CompanyAsset::create([
            'name' => $this->name,
            'asset_tag' => $this->asset_tag,
            'serial_number' => $this->serial_number,
            'purchase_price' => $this->purchase_price,
            'purchase_date' => $this->purchase_date,
            'useful_life_years' => $this->useful_life_years,
            'current_value' => $this->purchase_price, // Start value = purchase price
            'location' => $this->location,
            'condition' => $this->condition,
        ]);

        $this->dispatch('notify', message: 'Aset berhasil didaftarkan!', type: 'success');

        return redirect()->route('admin.aset.indeks');
    }

    public function render()
    {
        return view('livewire.assets.form');
    }
}
