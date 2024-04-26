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
        Schema::table('pcd_users', function (Blueprint $table) {
            $table->text('refresh_token')->nullable();
        });

        Schema::table('standar_user', function (Blueprint $table) {
            $table->text('refresh_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pcd_users', function (Blueprint $table) {
            $table->text('refresh_token');
        });
        Schema::table('standar_user', function (Blueprint $table) {
            $table->text('refresh_token');
        });
    }
};
