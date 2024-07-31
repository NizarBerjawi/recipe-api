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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('recipe_uuid')->references('uuid')->on('recipes');
            $table->foreignUuid('unit_uuid')->nullable()->references('uuid')->on('units');
            $table->float('quantity');
            $table->string('name');
            $table->text('display_text');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
