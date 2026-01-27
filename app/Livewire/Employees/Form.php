<?php

namespace App\Livewire\Employees;

use App\Models\Peran;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

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

    // Data Personal
    public $nik = '';

    public $npwp = '';

    public $phone_number = '';

    public $place_of_birth = '';

    public $date_of_birth = '';

    public $address = '';

    // Legacy support jika masih dibutuhkan
    public $selectedPermissions = [];

    public function mount($id = null)
    {
        if (! Auth::user()->isAdmin()) {
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

                // Load Personal Data
                $this->nik = $this->user->employeeDetail->nik;
                $this->npwp = $this->user->employeeDetail->npwp;
                $this->phone_number = $this->user->employeeDetail->phone_number;
                $this->place_of_birth = $this->user->employeeDetail->place_of_birth;
                $this->date_of_birth = $this->user->employeeDetail->date_of_birth ? $this->user->employeeDetail->date_of_birth->format('Y-m-d') : '';
                $this->address = $this->user->employeeDetail->address;
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
            'nik' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:25',
            'phone_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        try {
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
                        'nik' => $this->nik,
                        'npwp' => $this->npwp,
                        'phone_number' => $this->phone_number,
                        'place_of_birth' => $this->place_of_birth,
                        'date_of_birth' => $this->date_of_birth ?: null,
                        'address' => $this->address,
                    ]
                );
            });

            $pesan = $this->user ? 'Data karyawan diperbarui.' : 'Karyawan baru berhasil ditambahkan.';
            $this->dispatch('notify', message: $pesan, type: 'success');

            return redirect()->route('employees.index');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Terjadi kesalahan: '.$e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.employees.form', [
            'peranList' => Peran::all(),
        ]);
    }
}
