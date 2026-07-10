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
        Schema::table('sejarah', function (Blueprint $table) {

            if (! Schema::hasColumn('sejarah', 'media')) {
                $table->json('media')->nullable()->after('image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sejarah', function (Blueprint $table) {
            if (Schema::hasColumn('sejarah', 'media')) {
                $table->dropColumn('media');
            }
        });
    }
};
