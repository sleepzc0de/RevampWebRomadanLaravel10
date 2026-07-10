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
        Schema::create('struktur_organisasi_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('struktur_organisasi_id');
            $table->string('image_path');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('struktur_organisasi_id')
                ->references('id')
                ->on('struktur_organisasi')
                ->onDelete('cascade');
        });

        // Add video support to the main struktur_organisasi table
        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->text('video_url')->nullable()->after('image');
            $table->enum('layout_type', ['standard', 'wide', 'compact'])->default('standard')->after('video_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('struktur_organisasi_images');

        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->dropColumn('video_url');
            $table->dropColumn('layout_type');
        });
    }
};
