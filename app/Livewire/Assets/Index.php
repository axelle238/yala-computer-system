<?php

namespace App\Livewire\Assets;

use App\Models\AssetDepreciation;
use App\Models\CompanyAsset;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
#[Title('Manajemen Aset & Inventaris Kantor')]
class Index extends Component
{
    use WithPagination;

    // View State
    public $showCreateModal = false;
    public $showDetailModal = false;
    public $selectedAsset;

    // Input Form
    public $name;
    public $asset_tag;
    public $category = 'Elektronik';
    public $purchase_date;
    public $purchase_price;
    public $useful_life_years = 4;
    public $location;
    public $condition = 'good';

    public function mount()
    {
        $this->purchase_date = date('Y-m-d');
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'asset_tag' => 'required|unique:company_assets,asset_tag',
            'purchase_price' => 'required|numeric|min:0',
            'useful_life_years' => 'required|integer|min:1',
        ]);

        DB::transaction(function () {
            // 1. Create Asset
            $asset = CompanyAsset::create([
                'name' => $this->name,
                'asset_tag' => $this->asset_tag,
                'category' => $this->category,
                'purchase_date' => $this->purchase_date,
                'purchase_price' => $this->purchase_price,
                'current_value' => $this->purchase_price, // Awal = Harga Beli
                'useful_life_years' => $this->useful_life_years,
                'location' => $this->location,
                'condition' => $this->condition,
            ]);

            // 2. Generate Depreciation Schedule (Straight Line)
            // Biaya Penyusutan Tahunan = Harga Perolehan / Umur Ekonomis
            $annualDepreciation = $this->purchase_price / $this->useful_life_years;
            $currentValue = $this->purchase_price;

            for ($i = 1; $i <= $this->useful_life_years; $i++) {
                $date = Carbon::parse($this->purchase_date)->addYears($i);
                $currentValue -= $annualDepreciation;
                
                if ($currentValue < 0) $currentValue = 0;

                AssetDepreciation::create([
                    'company_asset_id' => $asset->id,
                    'depreciation_date' => $date,
                    'amount' => $annualDepreciation,
                    'book_value_after' => $currentValue,
                ]);
            }
        });

        session()->flash('success', 'Aset berhasil didaftarkan dan jadwal penyusutan dibuat.');
        $this->reset(['showCreateModal', 'name', 'asset_tag', 'purchase_price']);
    }

    public function viewDetail($id)
    {
        $this->selectedAsset = CompanyAsset::with('depreciations')->find($id);
        $this->showDetailModal = true;
    }

    public function render()
    {
        $assets = CompanyAsset::latest()->paginate(10);
        return view('livewire.assets.index', [
            'assets' => $assets
        ]);
    }
}