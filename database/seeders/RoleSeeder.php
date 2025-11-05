<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id'   => 1,
            'nama' => 'Super Admin'
        ]);

        Role::create([
            'id'   => 2,
            'nama' => 'Inventori'
        ]);

        Role::create([
            'id'   => 3,
            'nama' => 'Kasir'
        ]);
    }
}
