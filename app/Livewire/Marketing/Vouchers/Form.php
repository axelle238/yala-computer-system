<?php

namespace App\Livewire\Marketing\Vouchers;

use App\Models\Voucher;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Form Voucher - Yala Computer')]
class Form extends Component
{
    public $code;

    public $name;

    public $description;

    public $type = 'fixed'; // fixed, percent

    public $amount = 0;

    public $min_spend = 0;

    public $max_discount = 0;

    public $usage_limit = null;

    public $usage_per_user = 1;

    public $start_date;

    public $end_date;

    public $is_active = true;

    public function save()
    {
        $this->validate([
            'code' => 'required|unique:vouchers,code|alpha_dash|uppercase',
            'name' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'min_spend' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        Voucher::create([
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'amount' => $this->amount,
            'min_spend' => $this->min_spend,
            'max_discount' => $this->max_discount ?: null,
            'usage_limit' => $this->usage_limit ?: null,
            'usage_per_user' => $this->usage_per_user,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
        ]);

        $this->dispatch('notify', message: 'Voucher berhasil dibuat!', type: 'success');

        return redirect()->route('marketing.vouchers.index');
    }

    public function render()
    {
        return view('livewire.marketing.vouchers.form');
    }
}
