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
        // Add video_url column to visimisi table
        Schema::table('visimisi', function (Blueprint $table) {
            $table->text('video_url')->nullable()->after('image');
        });

        // Create visimisi_images table
        Schema::create('visimisi_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visimisi_id');
            $table->string('image');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('visimisi_id')
                  ->references('id')
                  ->on('visimisi')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop visimisi_images table
        Schema::dropIfExists('visimisi_images');

        // Remove video_url column from visimisi table
        Schema::table('visimisi', function (Blueprint $table) {
            $table->dropColumn('video_url');
        });
    }
};
