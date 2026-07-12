<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publikasi_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publikasi_id')->constrained('publikasi')->cascadeOnDelete();
            $table->string('judul');
            $table->string('sub_judul')->nullable();
            $table->longText('isi');
            $table->string('kategori')->nullable();
            $table->string('tipe')->nullable();
            $table->string('status')->nullable();
            $table->string('image')->nullable();
            $table->string('embedded_media')->nullable();
            $table->string('revised_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['publikasi_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publikasi_revisions');
    }
};
