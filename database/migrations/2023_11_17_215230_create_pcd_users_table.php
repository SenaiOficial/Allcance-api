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
            $table->string('phone_number', 11);
            $table->string('cpf', 11)->unique();
            $table->date('date_of_birth');
            $table->string('marital_status');
            $table->string('gender');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('cep');
            $table->string('country', 30);
            $table->string('state', 30);
            $table->string('city', 50);
            $table->string('neighborhood', 100);
            $table->string('street', 255);
            $table->string('street_number', 4);
            $table->string('street_complement', 255)->nullable();
            $table->string('color', 20);
            $table->boolean('job');
            $table->unsignedBigInteger('pcd_type');
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
