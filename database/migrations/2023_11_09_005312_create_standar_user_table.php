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
        Schema::create('standar_user', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number', 11);
            $table->string('cpf', 11)->unique();
            $table->date('date_of_birth');
            $table->string('marital_status', 30);
            $table->string('gender', 30);
            $table->string('state', 30);
            $table->string('city', 50);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('custom_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standar_user');
    }
};
