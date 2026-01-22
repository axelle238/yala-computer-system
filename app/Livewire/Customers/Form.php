<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Form Pelanggan - Yala Computer')]
class Form extends Component
{
    public $customer_id;
    public $name;
    public $phone;
    public $email;

    public function mount($id = null)
    {
        if ($id) {
            $customer = Customer::findOrFail($id);
            $this->customer_id = $customer->id;
            $this->name = $customer->name;
            $this->phone = $customer->phone;
            $this->email = $customer->email;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone,' . $this->customer_id,
            'email' => 'nullable|email|max:255',
        ]);

        Customer::updateOrCreate(
            ['id' => $this->customer_id],
            [
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'join_date' => $this->customer_id ? Customer::find($this->customer_id)->join_date : now(),
            ]
        );

        $this->dispatch('notify', message: 'Data pelanggan berhasil disimpan!', type: 'success');
        return redirect()->route('customers.index');
    }

    public function render()
    {
        return view('livewire.customers.form');
    }
}
