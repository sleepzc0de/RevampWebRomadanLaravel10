<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferensiKategori extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_kategori')->insert([
            ['nama_kategori' => 'bmn'],
            ['nama_kategori' => 'pengadaan'],
            ['nama_kategori' => 'other'],
        ]);
    }
}
