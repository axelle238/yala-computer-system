<?php

namespace App\Livewire\Admin;

use App\Models\Peran;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Form Peran & Hak Akses')]
class RoleForm extends Component
{
    public $nama;

    public $hak_akses = [];

    public $peranId;

    public $isEdit = false;

    // Definisi Hak Akses yang tersedia (Grouping)
    public $permissionsList = [
        'Dashboard' => [
            'dashboard' => 'Akses Dashboard Utama',
        ],
        'Penjualan (POS)' => [
            'akses_pos' => 'Akses Mesin Kasir (POS)',
            'lihat_pesanan' => 'Lihat Riwayat Pesanan',
            'kelola_pesanan' => 'Kelola Pesanan (Update Status)',
        ],
        'Inventaris & Produk' => [
            'akses_gudang' => 'Akses Menu Gudang',
            'lihat_produk' => 'Lihat Daftar Produk',
            'tambah_produk' => 'Tambah Produk Baru',
            'edit_produk' => 'Edit Data Produk',
            'hapus_produk' => 'Hapus Produk',
            'stok_opname' => 'Lakukan Stok Opname',
        ],
        'Servis & Layanan' => [
            'akses_servis' => 'Akses Menu Servis',
            'lihat_tiket' => 'Lihat Tiket Servis',
            'buat_tiket' => 'Buat Tiket Baru',
            'update_progres' => 'Update Progres Servis',
        ],
        'Keuangan' => [
            'akses_keuangan' => 'Akses Menu Keuangan',
            'lihat_laporan' => 'Lihat Laporan Keuangan',
            'kelola_kas' => 'Kelola Kas & Expenses',
        ],
        'SDM & Karyawan' => [
            'lihat_karyawan' => 'Lihat Data Karyawan',
            'kelola_karyawan' => 'Tambah/Edit Karyawan',
            'kelola_gaji' => 'Kelola Penggajian (Payroll)',
        ],
        'CRM & Pelanggan' => [
            'lihat_pelanggan' => 'Lihat Data Pelanggan',
            'kelola_pelanggan' => 'Kelola Data Pelanggan',
            'akses_chat' => 'Akses Live Chat & Inbox',
        ],
        'Sistem' => [
            'akses_admin' => 'Akses Penuh Administrator',
            'kelola_pengaturan' => 'Ubah Pengaturan Sistem',
            'lihat_log' => 'Lihat Log Aktivitas',
        ],
    ];

    public function mount($id = null)
    {
        if ($id) {
            $peran = Peran::findOrFail($id);
            $this->peranId = $id;
            $this->nama = $peran->nama;
            $this->hak_akses = $peran->hak_akses ?? [];
            $this->isEdit = true;
        } else {
            $this->hak_akses = [];
        }
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|unique:peran,nama,'.$this->peranId,
            'hak_akses' => 'array',
        ]);

        Peran::updateOrCreate(
            ['id' => $this->peranId],
            [
                'nama' => $this->nama,
                'hak_akses' => $this->hak_akses,
            ]
        );

        session()->flash('success', $this->isEdit ? 'Peran berhasil diperbarui.' : 'Peran baru berhasil dibuat.');

        return redirect()->route('employees.roles');
    }

    public function render()
    {
        return view('livewire.admin.role-form');
    }
}
