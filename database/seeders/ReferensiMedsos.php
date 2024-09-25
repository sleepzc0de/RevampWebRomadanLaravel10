<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferensiMedsos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('medsos')->insert([
            ['nama_medsos' => 'Facebook','link_medsos'=>'https://www.facebook.com/pastikanasetkita/?locale=id_ID','logo_medsos'=>'fa-brands fa-facebook'],
        ]);
    }
}
