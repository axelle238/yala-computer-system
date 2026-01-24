<?php

namespace App\Livewire\Assets;

use App\Models\CompanyAsset;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Aset Inventaris Toko - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showForm = false;
    
    // Model Props
    public $assetId;
    public $name;
    public $asset_code;
    public $serial_number;
    public $category; // Electronics, Furniture, Vehicle
    public $purchase_date;
    public $purchase_cost;
    public $condition = 'good'; // good, fair, poor, broken
    public $status = 'active'; // active, maintenance, disposed
    public $assigned_to_user_id;
    public $location;

    public function create()
    {
        $this->reset();
        $this->asset_code = 'AST-' . date('Y') . '-' . rand(1000,9999);
        $this->showForm = true;
    }

    public function edit($id)
    {
        $asset = CompanyAsset::findOrFail($id);
        $this->assetId = $asset->id;
        $this->name = $asset->name;
        $this->asset_code = $asset->asset_code;
        $this->serial_number = $asset->serial_number;
        $this->category = $asset->category;
        $this->purchase_date = $asset->purchase_date?->format('Y-m-d');
        $this->purchase_cost = $asset->purchase_cost;
        $this->condition = $asset->condition;
        $this->status = $asset->status;
        $this->assigned_to_user_id = $asset->assigned_to_user_id;
        $this->location = $asset->location;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'asset_code' => 'required|unique:company_assets,asset_code,'.$this->assetId,
            'purchase_cost' => 'nullable|numeric',
        ]);

        $data = [
            'name' => $this->name,
            'asset_code' => $this->asset_code,
            'serial_number' => $this->serial_number,
            'category' => $this->category,
            'purchase_date' => $this->purchase_date,
            'purchase_cost' => $this->purchase_cost,
            'condition' => $this->condition,
            'status' => $this->status,
            'assigned_to_user_id' => $this->assigned_to_user_id,
            'location' => $this->location,
        ];

        CompanyAsset::updateOrCreate(['id' => $this->assetId], $data);

        $this->dispatch('notify', message: 'Data aset berhasil disimpan.', type: 'success');
        $this->showForm = false;
    }

    public function delete($id)
    {
        CompanyAsset::find($id)->delete();
        $this->dispatch('notify', message: 'Aset dihapus.');
    }

    public function render()
    {
        $assets = CompanyAsset::with('assignee')
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('asset_code', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.assets.index', [
            'assets' => $assets,
            'users' => User::all()
        ]);
    }
}
