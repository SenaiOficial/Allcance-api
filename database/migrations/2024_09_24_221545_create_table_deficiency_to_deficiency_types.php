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
        Schema::create('deficiency_to_deficiency_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deficiency_id');
            $table->unsignedBigInteger('deficiency_types_id');
            $table->timestamps();

            $table->foreign('deficiency_id')
                  ->references('id')
                  ->on('deficiency')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');

            $table->foreign('deficiency_types_id')
                  ->references('id')
                  ->on('deficiency_types')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_deficiency_to_deficiency_types');
    }
};
