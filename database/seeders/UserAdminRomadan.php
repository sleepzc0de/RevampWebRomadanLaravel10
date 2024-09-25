<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAdminRomadan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['name' => 'Admin Romadan','email'=>'admin@romadan.kemenkeu.go.id','password'=>bcrypt('4dM!nR00M4D4N2O23!))(!((^!#!$!(')],
        ]);
    }
}
