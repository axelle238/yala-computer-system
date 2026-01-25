<?php

namespace App\Livewire\Assets;

use App\Models\CompanyAsset;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Layout('layouts.admin')]
#[Title('Manajemen Aset Tetap')]
class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $showForm = false;
    public $showDepreciationModal = false;

    // Form Properties
    public $assetId;
    public $name, $serial_number, $purchase_date, $purchase_cost, $useful_life_years, $condition = 'good', $location;
    public $image;

    // Calculation Data
    public $selectedAsset;
    public $depreciationSchedule = [];

    public function create()
    {
        $this->reset(['assetId', 'name', 'serial_number', 'purchase_date', 'purchase_cost', 'useful_life_years', 'condition', 'location', 'image']);
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|min:3',
            'purchase_date' => 'required|date',
            'purchase_cost' => 'required|numeric|min:0',
            'useful_life_years' => 'required|integer|min:1',
        ]);

        $path = null;
        if ($this->image) {
            $path = $this->image->store('assets', 'public');
        }

        CompanyAsset::updateOrCreate(['id' => $this->assetId], [
            'name' => $this->name,
            'serial_number' => $this->serial_number,
            'purchase_date' => $this->purchase_date,
            'purchase_cost' => $this->purchase_cost,
            'useful_life_years' => $this->useful_life_years,
            'condition' => $this->condition,
            'location' => $this->location,
            'image_path' => $path,
            // Calculate current value simply for now (Straight Line)
            'current_value' => $this->calculateCurrentValue($this->purchase_cost, $this->purchase_date, $this->useful_life_years),
        ]);

        $this->dispatch('notify', message: 'Data aset berhasil disimpan.', type: 'success');
        $this->showForm = false;
    }

    private function calculateCurrentValue($cost, $date, $years)
    {
        $ageInYears = Carbon::parse($date)->floatDiffInYears(now());
        if ($ageInYears >= $years) return 0;
        
        $yearlyDepreciation = $cost / $years;
        $value = $cost - ($yearlyDepreciation * $ageInYears);
        
        return max(0, $value);
    }

    public function edit($id)
    {
        $asset = CompanyAsset::findOrFail($id);
        $this->assetId = $asset->id;
        $this->name = $asset->name;
        $this->serial_number = $asset->serial_number;
        $this->purchase_date = $asset->purchase_date->format('Y-m-d');
        $this->purchase_cost = $asset->purchase_cost;
        $this->useful_life_years = $asset->useful_life_years;
        $this->condition = $asset->condition;
        $this->location = $asset->location;
        $this->showForm = true;
    }

    public function showDepreciation($id)
    {
        $this->selectedAsset = CompanyAsset::findOrFail($id);
        $this->generateSchedule();
        $this->showDepreciationModal = true;
    }

    private function generateSchedule()
    {
        $cost = $this->selectedAsset->purchase_cost;
        $years = $this->selectedAsset->useful_life_years;
        $yearlyDepreciation = $cost / $years;
        $purchaseYear = $this->selectedAsset->purchase_date->year;

        $this->depreciationSchedule = [];
        $currentVal = $cost;

        for ($i = 0; $i <= $years; $i++) {
            $year = $purchaseYear + $i;
            $this->depreciationSchedule[] = [
                'year' => $year,
                'start_value' => $currentVal,
                'depreciation' => $i == 0 ? 0 : $yearlyDepreciation, // Year 0 is purchase
                'end_value' => $i == 0 ? $cost : max(0, $currentVal - $yearlyDepreciation)
            ];
            if ($i > 0) $currentVal -= $yearlyDepreciation;
        }
    }

    public function delete($id)
    {
        CompanyAsset::destroy($id);
        $this->dispatch('notify', message: 'Aset dihapus.', type: 'success');
    }

    public function render()
    {
        $assets = CompanyAsset::where('name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        // Recalculate current values on fly for display accuracy
        foreach($assets as $asset) {
            $asset->current_value = $this->calculateCurrentValue($asset->purchase_cost, $asset->purchase_date, $asset->useful_life_years);
        }

        return view('livewire.assets.index', ['assets' => $assets]);
    }
}
