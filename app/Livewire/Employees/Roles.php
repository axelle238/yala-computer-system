<?php

namespace App\Livewire\Employees;

use App\Models\Role;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen Akses & Jabatan')]
class Roles extends Component
{
    public $permissionsList = [];
    public $showForm = false;
    
    // Form
    public $roleId;
    public $name;
    public $selectedPermissions = [];

    public function mount()
    {
        // Define system permissions map
        $this->permissionsList = [
            'POS' => ['access_pos', 'process_refund'],
            'Inventory' => ['view_product', 'create_product', 'edit_product', 'adjust_stock'],
            'Finance' => ['view_reports', 'manage_expenses', 'close_register'],
            'HRM' => ['view_employees', 'manage_roles', 'view_payroll'],
            'System' => ['update_settings', 'view_logs', 'backup_db'],
        ];
    }

    public function create()
    {
        $this->reset(['roleId', 'name', 'selectedPermissions']);
        $this->showForm = true;
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions ?? [];
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'required|array'
        ]);

        Role::updateOrCreate(['id' => $this->roleId], [
            'name' => $this->name,
            'permissions' => $this->selectedPermissions
        ]);
        
        $this->dispatch('notify', message: 'Role & Hak Akses berhasil disimpan.', type: 'success');
        $this->showForm = false;
    }

    public function delete($id)
    {
        $role = Role::find($id);
        if ($role->users()->exists()) {
            $this->dispatch('notify', message: 'Gagal! Masih ada user dengan role ini.', type: 'error');
            return;
        }
        
        $role->delete();
        $this->dispatch('notify', message: 'Role dihapus.', type: 'success');
    }

    public function render()
    {
        $roles = Role::latest()->get();
        return view('livewire.employees.roles', ['roles' => $roles, 'permissions' => $this->permissionsList]);
    }
}
