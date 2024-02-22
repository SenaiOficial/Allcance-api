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
        Schema::create('admin_user', function (Blueprint $table) {
            $table->id();
            $table->string('institution_name');
            $table->string('telephone', 10);
            $table->string('cnpj', 14)->unique();
            $table->string('pass_code')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_institution')->default(true);
            $table->string('custom_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_user');
    }
};
