<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferensiTipe extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_tipe')->insert([
            ['nama_tipe' => 'warta'],
            ['nama_tipe' => 'artikel'],
            ['nama_tipe' => 'berita'],
        ]);
    }
}
