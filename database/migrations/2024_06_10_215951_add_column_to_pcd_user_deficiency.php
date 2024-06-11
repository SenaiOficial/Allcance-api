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
        Schema::table('pcd_user_deficiency', function (Blueprint $table) {
            $table->unsignedBigInteger('deficiency_types_id')->after('deficiency_id');
            $table->foreign('deficiency_types_id')->references('id')->on('deficiency_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pcd_user_deficiency', function (Blueprint $table) {
            $table->dropColumn('deficiency_types_id');
        });
    }
};
