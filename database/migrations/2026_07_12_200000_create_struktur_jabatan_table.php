<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('struktur_jabatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan');
            // SQL Server melarang ON DELETE CASCADE/SET NULL pada FK
            // self-referencing, jadi pakai default (NO ACTION) — penghapusan
            // node yang masih punya anak/pejabat ditolak di controller.
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('struktur_jabatan');
        });

        // Seed hirarki jabatan sesuai struktur yang ditetapkan:
        // Kepala Biro -> [Kepala Bagian -> [Kepala Subbagian -> Pelaksana],
        //                                   [Jab. Fungsional Penyelia -> Mahir -> Terampil -> Pemula]]
        //             -> [Jab. Fungsional Madya -> Muda -> Pertama]
        $now = now();
        $insert = function (string $nama, ?int $parentId, int $urutan) use ($now) {
            return DB::table('struktur_jabatan')->insertGetId([
                'nama_jabatan' => $nama,
                'parent_id' => $parentId,
                'urutan' => $urutan,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        };

        $kepalaBiro = $insert('Kepala Biro Manajemen BMN dan Pengadaan', null, 1);
        $kepalaBagian = $insert('Kepala Bagian', $kepalaBiro, 1);
        $fungsionalMadya = $insert('Jabatan Fungsional Madya', $kepalaBiro, 2);

        $kepalaSubbagian = $insert('Kepala Subbagian', $kepalaBagian, 1);
        $fungsionalPenyelia = $insert('Jabatan Fungsional Penyelia', $kepalaBagian, 2);

        $insert('Pelaksana', $kepalaSubbagian, 1);

        $fungsionalMahir = $insert('Jabatan Fungsional Mahir', $fungsionalPenyelia, 1);
        $fungsionalTerampil = $insert('Jabatan Fungsional Terampil', $fungsionalMahir, 1);
        $insert('Jabatan Fungsional Pemula', $fungsionalTerampil, 1);

        $fungsionalMuda = $insert('Jabatan Fungsional Muda', $fungsionalMadya, 1);
        $insert('Jabatan Fungsional Pertama', $fungsionalMuda, 1);
    }

    public function down(): void
    {
        Schema::dropIfExists('struktur_jabatan');
    }
};
