<?php

namespace App\Livewire\Employees;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Hak Akses - Yala Computer')]
class Roles extends Component
{
    public $roles;
    public $permissions;
    
    // Form
    public $name;
    public $slug;
    public $selectedPermissions = [];
    public $editingRoleId = null;
    public $showForm = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->roles = Role::with('permissions')->get();
        $this->permissions = Permission::all();
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->editingRoleId = $id;
        $this->name = $role->name;
        $this->slug = $role->slug;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->editingRoleId = null;
        $this->name = '';
        $this->slug = '';
        $this->selectedPermissions = [];
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:50|unique:roles,slug,' . $this->editingRoleId,
            'selectedPermissions' => 'array'
        ]);

        if ($this->editingRoleId) {
            $role = Role::find($this->editingRoleId);
            $role->update([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
        } else {
            $role = Role::create([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
        }

        $role->permissions()->sync($this->selectedPermissions);

        $this->showForm = false;
        $this->loadData();
        $this->dispatch('notify', message: 'Role berhasil disimpan!', type: 'success');
    }

    public function delete($id)
    {
        // Prevent deleting core roles
        $role = Role::find($id);
        if (in_array($role->slug, ['admin', 'owner'])) {
            $this->dispatch('notify', message: 'Role utama tidak dapat dihapus.', type: 'error');
            return;
        }

        $role->delete();
        $this->loadData();
        $this->dispatch('notify', message: 'Role dihapus.');
    }

    public function render()
    {
        return view('livewire.employees.roles');
    }
}
