<?php

namespace App\Livewire\Marketing\Vouchers;

use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

#[Layout('layouts.admin')]
#[Title('Voucher & Kupon - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $showForm = false;
    public $search = '';

    // Model Props
    public $voucherId;
    public $code;
    public $type = 'fixed'; // fixed, percent
    public $discount_value;
    public $max_discount_amount;
    public $min_spend = 0;
    public $usage_limit = 100;
    public $start_date;
    public $end_date;
    public $is_active = true;

    public function create()
    {
        $this->reset();
        $this->code = strtoupper(Str::random(8));
        $this->start_date = now()->format('Y-m-d\TH:i');
        $this->end_date = now()->addMonth()->format('Y-m-d\TH:i');
        $this->showForm = true;
    }

    public function generateCode()
    {
        $this->code = strtoupper(Str::random(8));
    }

    public function edit($id)
    {
        $v = Voucher::findOrFail($id);
        $this->voucherId = $v->id;
        $this->code = $v->code;
        $this->type = $v->type;
        $this->discount_value = $v->discount_value;
        $this->max_discount_amount = $v->max_discount_amount;
        $this->min_spend = $v->min_spend;
        $this->usage_limit = $v->usage_limit;
        $this->start_date = $v->start_date->format('Y-m-d\TH:i');
        $this->end_date = $v->end_date->format('Y-m-d\TH:i');
        $this->is_active = $v->is_active;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'code' => 'required|unique:vouchers,code,'.$this->voucherId,
            'discount_value' => 'required|numeric|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $data = [
            'code' => strtoupper($this->code),
            'type' => $this->type,
            'discount_value' => $this->discount_value,
            'max_discount_amount' => $this->max_discount_amount,
            'min_spend' => $this->min_spend,
            'usage_limit' => $this->usage_limit,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
        ];

        Voucher::updateOrCreate(['id' => $this->voucherId], $data);

        $this->dispatch('notify', message: 'Voucher berhasil disimpan.', type: 'success');
        $this->showForm = false;
    }

    public function toggleStatus($id)
    {
        $v = Voucher::find($id);
        $v->update(['is_active' => !$v->is_active]);
    }

    public function delete($id)
    {
        Voucher::find($id)->delete();
        $this->dispatch('notify', message: 'Voucher dihapus.');
    }

    public function render()
    {
        $vouchers = Voucher::where('code', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.marketing.vouchers.index', ['vouchers' => $vouchers]);
    }
}
