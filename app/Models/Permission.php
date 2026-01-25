<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    // Model ini mungkin tidak diperlukan jika kita menggunakan array JSON di model Peran.
    // Namun untuk struktur yang lebih ketat, bisa digunakan untuk menyimpan daftar hak akses yang tersedia.
    // Untuk saat ini, kita akan mendefinisikan daftar hak akses di controller saja sebagai konstanta.
}
