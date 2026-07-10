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
        // Update the existing layanan table to increase content limit and add video_url
        Schema::table('layanan', function (Blueprint $table) {
            // Modify the layanan column to allow up to 5000 characters
            $table->text('layanan')->change();

            // Add video URL field
            $table->string('video_url')->nullable()->after('image');
        });

        // Create new table for multiple images
        Schema::create('layanan_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('layanan_id');
            $table->string('image_path');
            $table->timestamps();

            $table->foreign('layanan_id')
                ->references('id')
                ->on('layanan')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the layanan_images table
        Schema::dropIfExists('layanan_images');

        // Revert changes to layanan table
        Schema::table('layanan', function (Blueprint $table) {
            $table->string('layanan', 1000)->change(); // Change back to original limit
            $table->dropColumn('video_url');
        });
    }
};
