<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferensiStatus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_status')->insert([
            ['nama_status' => 'published'],
            ['nama_status' => 'draft'],
            ['nama_status' => 'scheduled'],
        ]);
    }
}
