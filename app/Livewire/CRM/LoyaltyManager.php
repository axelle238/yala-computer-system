<?php

namespace App\Livewire\CRM;

use App\Models\CustomerGroup;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Loyalty & Membership - Yala Computer')]
class LoyaltyManager extends Component
{
    public $groups;
    
    public $name;
    public $code;
    public $min_spend;
    public $discount_percent;
    public $color = 'gray';
    public $groupIdToEdit;
    public $showModal = false;

    public function mount()
    {
        $this->loadGroups();
    }

    public function loadGroups()
    {
        $this->groups = CustomerGroup::orderBy('min_spend')->get();
    }

    public function create()
    {
        $this->reset(['name', 'code', 'min_spend', 'discount_percent', 'color', 'groupIdToEdit']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $group = CustomerGroup::findOrFail($id);
        $this->groupIdToEdit = $id;
        $this->name = $group->name;
        $this->code = $group->code;
        $this->min_spend = $group->min_spend;
        $this->discount_percent = $group->discount_percent;
        $this->color = $group->color;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:customer_groups,code,' . $this->groupIdToEdit,
            'min_spend' => 'required|numeric',
            'discount_percent' => 'required|numeric|max:100',
        ]);

        $data = [
            'name' => $this->name,
            'code' => $this->code,
            'min_spend' => $this->min_spend,
            'discount_percent' => $this->discount_percent,
            'color' => $this->color,
        ];

        if ($this->groupIdToEdit) {
            CustomerGroup::find($this->groupIdToEdit)->update($data);
        } else {
            CustomerGroup::create($data);
        }

        $this->showModal = false;
        $this->loadGroups();
        $this->dispatch('notify', message: 'Level membership disimpan.', type: 'success');
    }

    public function delete($id)
    {
        try {
            CustomerGroup::findOrFail($id)->delete();
            $this->loadGroups();
            $this->dispatch('notify', message: 'Level dihapus.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Gagal hapus (Masih ada member).', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.c-r-m.loyalty-manager');
    }
}
