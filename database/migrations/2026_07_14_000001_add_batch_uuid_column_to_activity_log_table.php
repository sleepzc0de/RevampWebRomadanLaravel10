<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Kolom standar spatie/laravel-activitylog v4 — v4 selalu menyertakan
// batch_uuid pada INSERT, sehingga tanpa kolom ini SEMUA pencatatan
// aktivitas gagal (QueryException: Invalid column name 'batch_uuid').
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->uuid('batch_uuid')->nullable()->after('properties');
        });
    }

    public function down(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropColumn('batch_uuid');
        });
    }
};
