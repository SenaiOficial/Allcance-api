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
            $table->boolean('get_transport')->default(false)->after('needed_assistance');
            $table->string('transport_access')->default(false)->after('get_transport');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pcd_users', function (Blueprint $table) {
            $table->dropColumn('get_transport');
            $table->dropColumn('transport_access');
        });
    }
};
