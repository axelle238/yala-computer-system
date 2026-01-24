<?php

namespace App\Livewire\Marketing\Vouchers;

use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Voucher & Diskon - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function toggleStatus($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->update(['is_active' => !$voucher->is_active]);
        $this->dispatch('notify', message: 'Status voucher diperbarui.', type: 'success');
    }

    public function delete($id)
    {
        $voucher = Voucher::findOrFail($id);
        if ($voucher->usages()->exists()) {
            $this->dispatch('notify', message: 'Tidak bisa menghapus voucher yang sudah digunakan.', type: 'error');
            return;
        }
        $voucher->delete();
        $this->dispatch('notify', message: 'Voucher dihapus.', type: 'success');
    }

    public function render()
    {
        $vouchers = Voucher::withCount('usages')
            ->when($this->search, fn($q) => $q->where('code', 'like', '%'.$this->search.'%')->orWhere('name', 'like', '%'.$this->search.'%'))
            ->latest()
            ->paginate(10);

        return view('livewire.marketing.vouchers.index', [
            'vouchers' => $vouchers
        ]);
    }
}
