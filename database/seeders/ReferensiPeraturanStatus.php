<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferensiPeraturanStatus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_peraturan_status')->insert([
            ['nama_peraturan_status' => 'Berlaku'],
            ['nama_peraturan_status' => 'Tidak Berlaku'],
        ]);
    }
}
