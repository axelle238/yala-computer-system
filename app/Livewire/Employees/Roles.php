<?php

namespace App\Livewire\Employees;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
#[Title('Manajemen Jabatan & Hak Akses - Yala Computer')]
class Roles extends Component
{
    public $roles;
    public $allPermissions;
    
    // Form Data
    public $name;
    public $selectedPermissions = []; // Array of Permission IDs
    public $roleIdToEdit = null;
    public $isModalOpen = false;

    public function mount()
    {
        $this->allPermissions = Permission::all()->groupBy('group');
        $this->loadRoles();
    }

    public function loadRoles()
    {
        $this->roles = Role::with('permissions', 'users')->get();
    }

    public function create()
    {
        $this->reset(['name', 'selectedPermissions', 'roleIdToEdit']);
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->roleIdToEdit = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|unique:roles_v2,name,' . $this->roleIdToEdit,
            'selectedPermissions' => 'array'
        ]);

        if ($this->roleIdToEdit) {
            $role = Role::findOrFail($this->roleIdToEdit);
            $role->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name)
            ]);
        } else {
            $role = Role::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name)
            ]);
        }

        $role->permissions()->sync($this->selectedPermissions);

        $this->isModalOpen = false;
        $this->loadRoles();
        $this->dispatch('notify', message: 'Jabatan berhasil disimpan!', type: 'success');
    }

    public function delete($id)
    {
        $role = Role::withCount('users')->findOrFail($id);
        
        if ($role->users_count > 0) {
            $this->dispatch('notify', message: 'Gagal! Masih ada pegawai dengan jabatan ini.', type: 'error');
            return;
        }

        $role->delete();
        $this->loadRoles();
        $this->dispatch('notify', message: 'Jabatan dihapus.', type: 'success');
    }

    public function render()
    {
        return view('livewire.employees.roles');
    }
}