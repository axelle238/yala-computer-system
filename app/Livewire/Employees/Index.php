<?php

namespace App\Livewire\Employees;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen Pegawai - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showForm = false;
    
    // Model Props
    public $userId;
    public $name;
    public $email;
    public $role = 'technician';
    public $password;
    public $phone;
    public $salary;

    public function create()
    {
        $this->reset();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->phone = $user->phone;
        $this->salary = $user->salary; // Assuming 'salary' column exists from previous migrations or needs to be added
        $this->showForm = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$this->userId,
            'role' => 'required',
        ];

        if (!$this->userId) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'phone' => $this->phone,
            'salary' => $this->salary,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->userId], $data);

        $this->dispatch('notify', message: 'Data pegawai berhasil disimpan.', type: 'success');
        $this->showForm = false;
    }

    public function delete($id)
    {
        if ($id == auth()->id()) {
            $this->dispatch('notify', message: 'Tidak dapat menghapus akun sendiri.', type: 'error');
            return;
        }
        User::find($id)->delete();
        $this->dispatch('notify', message: 'Pegawai dihapus.');
    }

    public function render()
    {
        $employees = User::whereIn('role', ['admin', 'technician', 'cashier', 'warehouse', 'owner', 'hr'])
            ->where(function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.employees.index', [
            'employees' => $employees
        ]);
    }
}