<?php

namespace App\Livewire\Master;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Master Supplier - Yala Computer')]
class Suppliers extends Component
{
    use WithPagination;

    public $search = '';
    public $name, $email, $phone, $address, $supplier_id;
    public $isModalOpen = false;

    public function render()
    {
        $suppliers = Supplier::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.master.suppliers', ['suppliers' => $suppliers]);
    }

    public function create()
    {
        $this->reset(['name', 'email', 'phone', 'address', 'supplier_id']);
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->supplier_id = $id;
        $this->name = $supplier->name;
        $this->email = $supplier->email;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;
        $this->isModalOpen = true;
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
        $this->isModalOpen = false;
    }

    public function delete($id)
    {
        Supplier::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Supplier dihapus.', type: 'info');
    }
}
