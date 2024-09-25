<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InformasiPublik extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('informasi_publik')->insert([
            ['judul_list_informasi' => 'Peraturan','isi_list_informasi'=>'<p>Peraturan</p>','link_list_informasi'=>'informasi-publik-peraturan-index-fe'],
            ['judul_list_informasi' => 'Pedoman','isi_list_informasi'=>'<p>Pedoman</p>','link_list_informasi'=>'informasi-publik-pedoman-index-fe'],
            ['judul_list_informasi' => 'Aplikasi','isi_list_informasi'=>'<p>Aplikasi</p>','link_list_informasi'=>'informasi-publik-aplikasi-index-fe'],
        ]);
    }
}
