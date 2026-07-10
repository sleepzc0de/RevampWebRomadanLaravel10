<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom-kolom ini dipakai untuk filter (kategori/status/tipe) dan
     * pengurutan (updated_at) pada query beranda & pencarian publikasi.
     * Tanpa index, query melambat linear seiring bertambahnya data.
     */
    public function up(): void
    {
        Schema::table('publikasi', function (Blueprint $table) {
            $table->index('kategori', 'publikasi_kategori_index');
            $table->index('status', 'publikasi_status_index');
            $table->index('tipe', 'publikasi_tipe_index');
            $table->index('updated_at', 'publikasi_updated_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('publikasi', function (Blueprint $table) {
            $table->dropIndex('publikasi_kategori_index');
            $table->dropIndex('publikasi_status_index');
            $table->dropIndex('publikasi_tipe_index');
            $table->dropIndex('publikasi_updated_at_index');
        });
    }
};
