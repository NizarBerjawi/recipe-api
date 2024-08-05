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
        Schema::create('ingredient_recipe', function (Blueprint $table) {
            $table->foreignUuid('ingredient_uuid')->references('uuid')->on('ingredients');
            $table->foreignUuid('recipe_uuid')->references('uuid')->on('recipes');
            $table->foreignUuid('unit_uuid')->references('uuid')->on('units');
            $table->float('quantity');

            $table->primary(['ingredient_uuid', 'recipe_uuid']);
            $table->unique(['ingredient_uuid', 'recipe_uuid', 'unit_uuid']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_recipe');
    }
};
