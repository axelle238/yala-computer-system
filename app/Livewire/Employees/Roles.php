<?php

namespace App\Livewire\Employees;

use App\Models\Role;
use App\Models\Permission;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen Akses & Jabatan')]
class Roles extends Component
{
    public $roles = [];
    public $permissions = [];
    
    // Form
    public $showForm = false;
    public $roleId;
    public $name;
    public $selectedPermissions = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Mock Data Logic (Asuming Spatie Permission or Custom Table structure)
        // In real app: Role::with('permissions')->get();
        
        $this->permissions = [
            'pos' => ['access_pos', 'process_refund'],
            'inventory' => ['view_product', 'create_product', 'edit_product', 'adjust_stock'],
            'finance' => ['view_reports', 'manage_expenses', 'close_register'],
            'users' => ['view_employees', 'manage_roles'],
            'settings' => ['update_settings', 'view_logs'],
        ];

        // Mock Roles if DB empty or not migrated
        $this->roles = collect([
            ['id' => 1, 'name' => 'Super Admin', 'permissions' => ['*']],
            ['id' => 2, 'name' => 'Store Manager', 'permissions' => ['view_reports', 'manage_expenses', 'view_product', 'adjust_stock']],
            ['id' => 3, 'name' => 'Kasir', 'permissions' => ['access_pos']],
            ['id' => 4, 'name' => 'Teknisi', 'permissions' => ['view_product']],
        ]);
    }

    public function create()
    {
        $this->reset(['roleId', 'name', 'selectedPermissions']);
        $this->showForm = true;
    }

    public function edit($id)
    {
        // Mock finding role
        $role = $this->roles->firstWhere('id', $id);
        
        $this->roleId = $role['id'];
        $this->name = $role['name'];
        $this->selectedPermissions = $role['permissions'];
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'selectedPermissions' => 'required|array'
        ]);

        // Logic to save to DB would go here
        // Role::updateOrCreate(...)
        
        $this->dispatch('notify', message: 'Role & Hak Akses berhasil disimpan.', type: 'success');
        $this->showForm = false;
    }

    public function delete($id)
    {
        // Role::destroy($id);
        $this->dispatch('notify', message: 'Role dihapus.', type: 'success');
    }

    public function render()
    {
        return view('livewire.employees.roles');
    }
}