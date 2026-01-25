<?php

namespace App\Livewire\Marketing\Vouchers;

use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen Voucher & Promo')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showForm = false;
    
    // Form Inputs
    public $voucherId;
    public $code, $type = 'fixed', $amount, $min_spend = 0, $quota = 100, $start_date, $end_date;
    public $is_active = true;

    public function create()
    {
        $this->reset(['voucherId', 'code', 'type', 'amount', 'min_spend', 'quota', 'start_date', 'end_date', 'is_active']);
        $this->code = strtoupper(\Illuminate\Support\Str::random(8));
        $this->showForm = true;
    }

    public function edit($id)
    {
        $v = Voucher::findOrFail($id);
        $this->voucherId = $v->id;
        $this->code = $v->code;
        $this->type = $v->type;
        $this->amount = $v->amount;
        $this->min_spend = $v->min_spend;
        $this->quota = $v->quota;
        $this->start_date = $v->start_date ? $v->start_date->format('Y-m-d') : null;
        $this->end_date = $v->end_date ? $v->end_date->format('Y-m-d') : null;
        $this->is_active = $v->is_active;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'code' => 'required|unique:vouchers,code,' . $this->voucherId,
            'type' => 'required|in:fixed,percentage',
            'amount' => 'required|numeric|min:1',
            'quota' => 'required|numeric|min:1',
        ]);

        Voucher::updateOrCreate(['id' => $this->voucherId], [
            'code' => strtoupper($this->code),
            'type' => $this->type,
            'amount' => $this->amount,
            'min_spend' => $this->min_spend,
            'quota' => $this->quota,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
        ]);

        $this->showForm = false;
        $this->dispatch('notify', message: 'Voucher berhasil disimpan.', type: 'success');
    }

    public function delete($id)
    {
        Voucher::destroy($id);
        $this->dispatch('notify', message: 'Voucher dihapus.', type: 'success');
    }

    public function render()
    {
        $vouchers = Voucher::where('code', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.marketing.vouchers.index', ['vouchers' => $vouchers]);
    }
}