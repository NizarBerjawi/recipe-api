<?php

use App\Http\Controllers\DirectionController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecipeDetailController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(RecipeController::class)->group(function () {
    Route::get('/recipes', 'index')->name('recipe.index');
    Route::get('/recipes/{recipe}', 'show')->name('recipe.show');
});

Route::controller(IngredientController::class)->group(function () {
    Route::get('/ingredients', 'index')->name('ingredient.index');
    Route::get('/ingredients/{ingredient}', 'show')->name('ingredient.show');
});

Route::controller(RecipeDetailController::class)->group(function () {
    Route::get('/recipeDetails', 'index')->name('recipeDetail.index');
    Route::get('/recipeDetails/{recipeDetail}', 'show')->name('recipeDetail.show');

});

Route::controller(DirectionController::class)->group(function () {
    Route::get('/directions', 'index')->name('direction.index');
    Route::get('/directions/{direction}', 'show')->name('direction.show');
});

Route::controller(UnitController::class)->group(function () {
    Route::get('/units', 'index')->name('unit.index');
    Route::get('/units/{unit}', 'show')->name('unit.show');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index')->name('user.index');
    Route::get('/users/{user}', 'show')->name('user.show');
});
