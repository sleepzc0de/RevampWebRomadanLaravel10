<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('publikasi', function (Blueprint $table) {
            $table->text('embedded_media')->nullable()->after('isi')
                ->comment('Stores URLs for embedded videos, images or other media');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('publikasi', function (Blueprint $table) {
            $table->dropColumn('embedded_media');
        });
    }
};
