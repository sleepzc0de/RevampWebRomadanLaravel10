<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('platform', 100)->nullable();
            $table->string('device_type', 20)->nullable();
            $table->string('url', 500);
            $table->string('route_name', 150)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('session_id', 100)->nullable();
            $table->boolean('is_bot')->default(false);
            $table->timestamps();

            $table->index('route_name');
            $table->index('created_at');
            $table->index('session_id');
            $table->index('is_bot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
