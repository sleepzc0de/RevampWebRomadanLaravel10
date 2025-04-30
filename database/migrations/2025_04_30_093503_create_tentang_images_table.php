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
        Schema::table('tentang', function (Blueprint $table) {
            $table->string('video_url')->nullable()->after('image');
        });

        Schema::create('tentang_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tentang_id');
            $table->string('image_path');
            $table->timestamps();

            $table->foreign('tentang_id')
                  ->references('id')
                  ->on('tentang')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tentang', function (Blueprint $table) {
            $table->dropColumn('video_url');
        });

        Schema::dropIfExists('tentang_images');
    }
};
