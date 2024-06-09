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
        Schema::table('deficiency', function (Blueprint $table) {
            $table->unsignedBigInteger('deficiency_types_id')->nullable();
        });

        DB::statement('UPDATE deficiency SET deficiency_types_id = (SELECT id FROM deficiency_types LIMIT 1) WHERE deficiency_types_id IS NULL');

        Schema::table('deficiency', function (Blueprint $table) {
            $table->foreign('deficiency_types_id')->references('id')->on('deficiency_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deficiency', function (Blueprint $table) {
            //
        });
    }
};
