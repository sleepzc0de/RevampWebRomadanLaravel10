<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publikasi', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable()->after('status');
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::table('publikasi', function (Blueprint $table) {
            $table->dropIndex(['status', 'published_at']);
            $table->dropColumn('published_at');
        });
    }
};
