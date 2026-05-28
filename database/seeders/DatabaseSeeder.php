<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'admin',
            'email'    => 'admin@gmail.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'hp'       => '081234567890',
            'alamat'   => 'Kantor Pusat',
        ]);

        User::create([
            'name'     => 'aurel calista',
            'email'    => 'calstmaheswari@gmail.com',
            'username' => 'arel',
            'password' => Hash::make('12345678'),
            'role'     => 'user',
            'hp'       => '082345678901',
            'alamat'   => 'Jl. Mawar No. 10, Cirebon',
        ]);
    }
}