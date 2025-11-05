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
    }
}
