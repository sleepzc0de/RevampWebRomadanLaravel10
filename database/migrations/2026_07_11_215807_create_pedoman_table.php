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
        Schema::create('pedoman', function (Blueprint $table) {
            $table->id();
            $table->string('judul_pedoman');
            $table->text('deskripsi')->nullable();
            $table->string('file');
            $table->unsignedSmallInteger('kategori')->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedoman');
    }
};
