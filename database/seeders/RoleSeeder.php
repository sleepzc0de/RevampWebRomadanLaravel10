<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Daftar nama role yang akan dibuat
         $roles = [
            'ADMINISTRATOR',
            'REDAKTUR',
            'EDITOR',
            'HUMAS',
            'TAMU',
        ];

        // Loop untuk membuat role jika belum ada
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }
    }
}
