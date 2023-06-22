<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peraturan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_peraturan');
            $table->string('judul_peraturan');
            $table->string('file')->nullable();
            $table->unsignedSmallInteger('kategori')->nullable();
            $table->unsignedSmallInteger('jenis_peraturan')->nullable();
            $table->date('tanggal_penetapan')->nullable();
            $table->date('tanggal_berlaku')->nullable();
            $table->unsignedSmallInteger('status_peraturan')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peraturan');
    }
};
