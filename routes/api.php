<?php

use App\Http\Controllers\DirectionController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecipeDetailController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
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

Route::controller(DirectionController::class)->group(function () {
    Route::get('/directions', 'index');
});

Route::controller(UnitController::class)->group(function () {
    Route::get('/units', 'index');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');
});
