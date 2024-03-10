<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pcd_user_deficiency', function (Blueprint $table) {
            $table->unsignedBigInteger('pcd_user_id');
            $table->unsignedBigInteger('deficiency_id');
            $table->foreign('pcd_user_id')->references('id')->on('pcd_users')->onDelete('cascade');
            $table->foreign('deficiency_id')->references('id')->on('deficiency')->onDelete('cascade');
            $table->primary(['pcd_user_id', 'deficiency_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pcd_user_deficiency');
    }
};
