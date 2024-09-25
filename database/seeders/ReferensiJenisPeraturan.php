<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferensiJenisPeraturan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_jenis_peraturan')->insert([
            ['nama_jenis_peraturan' => 'PMK'],
            ['nama_jenis_peraturan' => 'KMK'],
            ['nama_jenis_peraturan' => 'PP'],
            ['nama_jenis_peraturan' => 'SE'],
        ]);
    }
}
