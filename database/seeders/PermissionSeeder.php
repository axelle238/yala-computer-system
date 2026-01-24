<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'Produk' => ['product.view', 'product.create', 'product.edit', 'product.delete'],
            'Stok' => ['stock.view', 'stock.adjust', 'stock.opname'],
            'Transaksi' => ['pos.access', 'order.view', 'order.refund'],
            'Laporan' => ['report.sales', 'report.finance'],
            'Pegawai' => ['employee.view', 'employee.manage', 'role.manage'],
            'Setting' => ['setting.view', 'setting.edit'],
        ];

        foreach ($permissions as $group => $items) {
            foreach ($items as $name) {
                Permission::firstOrCreate(
                    ['name' => $name],
                    ['group' => $group]
                );
            }
        }
    }
}