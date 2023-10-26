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
        Schema::create('pcd', function (Blueprint $table) {
            $table->id();
            $table->string('color');
            $table->boolean('job');
            $table->string('pcd_type');
            $table->string('pcd');
            $table->boolean('pcd_acquired');
            $table->boolean('needed_assistance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pcd');
    }
};
