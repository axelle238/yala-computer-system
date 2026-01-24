<?php

namespace App\Livewire\Assets;

use App\Models\CompanyAsset;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Aset Tetap - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function runDepreciation()
    {
        $assets = CompanyAsset::where('current_value', '>', 0)->get();
        $count = 0;
        
        foreach ($assets as $asset) {
            $asset->calculateDepreciation();
            $count++;
        }

        $this->dispatch('notify', message: "Depresiasi untuk $count aset berhasil dihitung.", type: 'success');
    }

    public function delete($id)
    {
        CompanyAsset::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Aset dihapus.', type: 'success');
    }

    public function render()
    {
        $assets = CompanyAsset::when($this->search, function($q) {
            $q->where('name', 'like', '%'.$this->search.'%')
              ->orWhere('asset_tag', 'like', '%'.$this->search.'%');
        })->latest()->paginate(10);

        return view('livewire.assets.index', [
            'assets' => $assets
        ]);
    }
}
