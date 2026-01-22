<?php

namespace App\Livewire\Master\Suppliers;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Form Supplier - Yala Computer')]
class Form extends Component
{
    public $supplier_id;
    public $name;
    public $email;
    public $phone;
    public $address;

    public function mount($id = null)
    {
        if ($id) {
            $supplier = Supplier::findOrFail($id);
            $this->supplier_id = $supplier->id;
            $this->name = $supplier->name;
            $this->email = $supplier->email;
            $this->phone = $supplier->phone;
            $this->address = $supplier->address;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        Supplier::updateOrCreate(
            ['id' => $this->supplier_id],
            [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address
            ]
        );

        $this->dispatch('notify', message: 'Supplier berhasil disimpan!', type: 'success');
        return redirect()->route('master.suppliers');
    }

    public function render()
    {
        return view('livewire.master.suppliers.form');
    }
}
