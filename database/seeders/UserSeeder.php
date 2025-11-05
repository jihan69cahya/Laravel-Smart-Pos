<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Jihan Super',
            'username' => 'superadmin',
            'email' => 'superadmin@pos.com',
            'password' => Hash::make('superadmin@'),
            'id_role' => 1
        ]);

        User::create([
            'nama' => 'Jihan Inventori',
            'username' => 'inventori',
            'email' => 'inventori@pos.com',
            'password' => Hash::make('inventori@'),
            'id_role' => 2
        ]);

        User::create([
            'nama' => 'Jihan Kasir',
            'username' => 'kasir',
            'email' => 'kasir@pos.com',
            'password' => Hash::make('kasir@'),
            'id_role' => 3
        ]);
    }
}
