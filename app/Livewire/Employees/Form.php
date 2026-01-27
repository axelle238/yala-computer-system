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

#[Layout('layouts.admin')]
#[Title('Formulir Data Karyawan - Yala Computer')]
class Form extends Component
{
    public ?User $karyawan = null;

    // Data Akun
    public $nama = '';

    public $surel = '';

    public $kataSandi = '';

    public $tipePeran = 'khusus'; // 'admin' (bawaan) atau 'khusus' (dari tabel peran)

    public $idPeranTerpilih = null;

    // Data Kompensasi
    public $gajiPokok = 0;

    public $uangHarian = 0;

    public $persentaseKomisi = 0;

    // Data Kontrak & Kepegawaian (Baru)
    public $tanggalBergabung = '';

    public $awalKontrak = '';

    public $akhirKontrak = '';

    public $statusKaryawan = 'Tetap'; // Tetap, Kontrak, Magang, Percobaan

    // Data Pribadi
    public $nik = '';

    public $npwp = '';

    public $nomorTelepon = '';

    public $tempatLahir = '';

    public $tanggalLahir = '';

    public $alamatLengkap = '';

    /**
     * Inisialisasi komponen
     */
    public function mount($id = null)
    {
        if (! Auth::user()->punyaAkses('kelola_karyawan') && ! Auth::user()->isAdmin()) {
            return abort(403);
        }

        if ($id) {
            $this->karyawan = User::with(['employeeDetail', 'peran'])->findOrFail($id);
            $this->isiDataForm($this->karyawan);
        } else {
            $this->tanggalBergabung = date('Y-m-d');
        }
    }

    /**
     * Mengisi form dengan data karyawan yang ada
     */
    public function isiDataForm($user)
    {
        $this->nama = $user->name;
        $this->surel = $user->email;

        // Logika Peran
        if ($user->role === 'admin') {
            $this->tipePeran = 'admin';
        } else {
            $this->tipePeran = 'khusus';
            $this->idPeranTerpilih = $user->id_peran;
        }

        // Muat Detail Karyawan
        if ($detail = $user->employeeDetail) {
            $this->gajiPokok = $detail->base_salary;
            $this->uangHarian = $detail->allowance_daily;
            $this->persentaseKomisi = $detail->commission_percentage;

            $this->tanggalBergabung = $detail->join_date ? $detail->join_date->format('Y-m-d') : '';

            // Data Pribadi
            $this->nik = $detail->nik;
            $this->npwp = $detail->npwp;
            $this->nomorTelepon = $detail->phone_number;
            $this->tempatLahir = $detail->place_of_birth;
            $this->tanggalLahir = $detail->date_of_birth ? $detail->date_of_birth->format('Y-m-d') : '';
            $this->alamatLengkap = $detail->address;

            // Data Kontrak (Jika kolom belum ada di DB, perlu migrasi nanti, tapi kita siapkan logicnya)
            // Asumsi: Kita akan menggunakan kolom json 'contract_details' atau kolom baru di migrasi terpisah jika kompleks.
            // Untuk saat ini, kita simpan di field tambahan jika ada, atau abaikan jika belum migrasi.
            // (Akan ditambahkan di logic save)
        }
    }

    /**
     * Menyimpan data karyawan
     */
    public function simpan()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'surel' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->karyawan->id ?? null)],
            'tipePeran' => 'required',
            'idPeranTerpilih' => 'required_if:tipePeran,khusus',
            'kataSandi' => $this->karyawan ? 'nullable|min:6' : 'required|min:6',
            'gajiPokok' => 'numeric|min:0',
            'tanggalBergabung' => 'nullable|date',
            'nik' => 'nullable|string|max:20',
            'nomorTelepon' => 'nullable|string|max:20',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'surel.unique' => 'Alamat surel sudah terdaftar.',
            'kataSandi.required' => 'Kata sandi wajib diisi untuk karyawan baru.',
            'kataSandi.min' => 'Kata sandi minimal 6 karakter.',
            'idPeranTerpilih.required_if' => 'Silakan pilih peran untuk karyawan.',
        ]);

        try {
            DB::transaction(function () {
                // 1. Tentukan Peran
                $kolomRole = 'employee';
                $peranId = null;

                if ($this->tipePeran === 'admin') {
                    $kolomRole = 'admin';
                } elseif ($this->tipePeran === 'khusus') {
                    $peranId = $this->idPeranTerpilih;
                }

                // 2. Data Akun Pengguna
                $dataAkun = [
                    'name' => $this->nama,
                    'email' => $this->surel,
                    'role' => $kolomRole,
                    'id_peran' => $peranId,
                ];

                if ($this->kataSandi) {
                    $dataAkun['password'] = bcrypt($this->kataSandi);
                }

                // 3. Simpan User
                if ($this->karyawan) {
                    $this->karyawan->update($dataAkun);
                    $user = $this->karyawan;
                } else {
                    $user = User::create($dataAkun);
                }

                // 4. Simpan Detail Karyawan
                $user->employeeDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'base_salary' => $this->gajiPokok,
                        'allowance_daily' => $this->uangHarian,
                        'commission_percentage' => $this->persentaseKomisi,
                        'join_date' => $this->tanggalBergabung ?: null,
                        'nik' => $this->nik,
                        'npwp' => $this->npwp,
                        'phone_number' => $this->nomorTelepon,
                        'place_of_birth' => $this->tempatLahir,
                        'date_of_birth' => $this->tanggalLahir ?: null,
                        'address' => $this->alamatLengkap,
                        // 'contract_status' => $this->statusKaryawan, // Perlu migrasi database jika kolom belum ada
                    ]
                );
            });

            $pesan = $this->karyawan ? 'Data karyawan berhasil diperbarui.' : 'Karyawan baru berhasil ditambahkan.';
            $this->dispatch('notify', message: $pesan, type: 'success');

            return redirect()->route('admin.karyawan.indeks');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Terjadi kesalahan sistem: '.$e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.employees.form', [
            'daftarPeran' => Peran::all(),
        ]);
    }
}
