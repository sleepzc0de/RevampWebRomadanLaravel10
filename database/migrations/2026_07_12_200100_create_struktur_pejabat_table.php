<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('struktur_pejabat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jabatan_id')->constrained('struktur_jabatan')->cascadeOnDelete();
            $table->string('nama');
            $table->string('foto')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('struktur_pejabat');
    }
};
