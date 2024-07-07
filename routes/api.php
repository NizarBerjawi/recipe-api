<?php

use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecipeDetailController;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Route;

Route::controller(RecipeController::class)->group(function () {
    Route::get('/recipes', 'index');
});

Route::controller(IngredientController::class)->group(function () {
    Route::get('/ingredients', 'index');
});

Route::controller(RecipeDetailController::class)->group(function () {
    Route::get('/recipeDetails', 'index');
});