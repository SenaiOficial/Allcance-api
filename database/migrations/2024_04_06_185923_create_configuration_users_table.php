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
        Schema::create('configuration_users', function (Blueprint $table) {
            $table->unsignedBigInteger('pcd_user_id');
            $table->unsignedBigInteger('text_size_id');
            $table->unsignedBigInteger('color_blindness_id');
            $table->foreign('pcd_user_id')->references('id')->on('pcd_users')->onDelete('cascade');
            $table->foreign('text_size_id')->references('id')->on('text_size')->onDelete('cascade');
            $table->foreign('color_blindness_id')->references('id')->on('color_blindness')->onDelete('cascade');
            $table->primary(['pcd_user_id', 'text_size_id', 'color_blindness_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuration_users');
    }
};
