<?php

namespace App\Livewire\Employees;

use App\Models\User;
use App\Models\Peran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Form Karyawan - Yala Computer')]
class Form extends Component
{
    public ?User $user = null;

    public $name = '';
    public $email = '';
    public $password = ''; 
    public $role_type = 'custom'; // 'admin' (built-in) atau 'custom' (dari tabel peran)
    public $selected_role_id = null; // ID dari tabel peran
    public $base_salary = 0;
    public $allowance_daily = 0;
    public $commission_percentage = 0;
    public $join_date = '';
    
    // Legacy support jika masih dibutuhkan
    public $selectedPermissions = [];

    public function mount($id = null)
    {
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }

        if ($id) {
            $this->user = User::with(['employeeDetail', 'peran'])->findOrFail($id);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            
            // Logika Role
            if ($this->user->role === 'admin') {
                $this->role_type = 'admin';
            } else {
                $this->role_type = 'custom';
                $this->selected_role_id = $this->user->id_peran;
            }

            // Load Employee Detail
            if ($this->user->employeeDetail) {
                $this->base_salary = $this->user->employeeDetail->base_salary;
                $this->allowance_daily = $this->user->employeeDetail->allowance_daily;
                $this->commission_percentage = $this->user->employeeDetail->commission_percentage;
                $this->join_date = $this->user->employeeDetail->join_date ? $this->user->employeeDetail->join_date->format('Y-m-d') : '';
            }
            
            // Legacy Permissions
            $this->selectedPermissions = $this->user->access_rights ?? [];
        } else {
            $this->join_date = date('Y-m-d');
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user->id ?? null)],
            'role_type' => 'required',
            'selected_role_id' => 'required_if:role_type,custom',
            'password' => $this->user ? 'nullable|min:6' : 'required|min:6',
            'base_salary' => 'numeric|min:0',
            'join_date' => 'nullable|date',
        ]);

        DB::transaction(function () {
            // 1. Tentukan Role
            $roleColumn = 'employee'; // Default legacy
            $peranId = null;

            if ($this->role_type === 'admin') {
                $roleColumn = 'admin';
            } elseif ($this->role_type === 'custom') {
                $peranId = $this->selected_role_id;
            }

            // 2. Data User
            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $roleColumn,
                'id_peran' => $peranId,
                // 'access_rights' legacy jika dibutuhkan
            ];

            if ($this->password) {
                $userData['password'] = bcrypt($this->password);
            }

            // 3. Simpan User
            if ($this->user) {
                $this->user->update($userData);
                $user = $this->user;
            } else {
                $user = User::create($userData);
            }

            // 4. Simpan Employee Detail
            $user->employeeDetail()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'base_salary' => $this->base_salary,
                    'allowance_daily' => $this->allowance_daily,
                    'commission_percentage' => $this->commission_percentage,
                    'join_date' => $this->join_date ?: null,
                ]
            );
        });

        session()->flash('success', $this->user ? 'Data karyawan diperbarui.' : 'Karyawan baru berhasil ditambahkan.');

        return redirect()->route('employees.index');
    }

    public function render()
    {
        return view('livewire.employees.form', [
            'peranList' => Peran::all()
        ]);
    }
}