<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserAdminRomadan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat role ADMINISTRATOR jika belum ada
        $adminRole = Role::firstOrCreate(['name' => 'ADMINISTRATOR']);

        // Buat user admin
        $admin = User::create([
            'name' => 'Admin Romadan',
            'email' => 'admin@romadan.kemenkeu.go.id',
            'password' => bcrypt('4dM!nR00M4D4N2O24!))(!((^!#!$!(')
        ]);

        // Assign role ADMINISTRATOR ke user admin
        $admin->assignRole('ADMINISTRATOR');
    }
}
