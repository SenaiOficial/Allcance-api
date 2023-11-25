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
        Schema::create('pcd_users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number');
            $table->string('cpf')->unique();
            $table->date('date_of_birth');
            $table->string('marital_status');
            $table->string('gender');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('cep');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('street');
            $table->string('street_number');
            $table->string('street_complement', 255)->nullable();
            $table->string('color');
            $table->boolean('job');
            $table->string('pcd_type');
            $table->json('pcd');
            $table->boolean('pcd_acquired');
            $table->boolean('needed_assistance');
            $table->string('custom_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pcd_users');
    }
};
