<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Update Admin Existing
        User::where('email', 'admin@yala.com')->update([
            'role' => 'admin',
            'access_rights' => null, // Admin has full access implicitly
        ]);

        // 2. Create Owner
        User::firstOrCreate([
            'email' => 'owner@yala.com',
        ], [
            'name' => 'Bapak Pemilik',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'access_rights' => null,
        ]);

        // 3. Create Employee (Contoh: Kasir)
        User::firstOrCreate([
            'email' => 'kasir@yala.com',
        ], [
            'name' => 'Andi Kasir',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'access_rights' => ['create_transaction', 'view_products'], // Hanya bisa transaksi & lihat produk
        ]);

        // 4. Create Employee (Contoh: Gudang)
        User::firstOrCreate([
            'email' => 'gudang@yala.com',
        ], [
            'name' => 'Budi Gudang',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'access_rights' => ['manage_products', 'manage_stock', 'view_products'], // Bisa edit produk & stok
        ]);
    }
}
