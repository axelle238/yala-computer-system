<?php

namespace App\Livewire\Employees;

use App\Models\Role;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Role - Yala Computer')]
class Roles extends Component
{
    public $name, $description;
    public $roles;

    public function render()
    {
        $this->roles = Role::all();
        return view('livewire.employees.roles');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create([
            'name' => $this->name,
            'description' => $this->description
        ]);

        $this->reset(['name', 'description']);
        $this->dispatch('notify', message: 'Role berhasil ditambahkan!', type: 'success');
    }

    public function delete($id)
    {
        Role::find($id)->delete();
        $this->dispatch('notify', message: 'Role dihapus.', type: 'info');
    }
}
