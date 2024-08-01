<?php

use App\Http\Controllers\DirectionController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecipeDetailController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(RecipeController::class)->group(function () {
    Route::get('/recipes', 'index')->name('recipes.index');
    Route::post('/recipes', 'store')->name('recipes.store');
    Route::get('/recipes/{recipe}', 'show')->name('recipes.show');
    Route::delete('/recipes/{recipe}', 'destroy')->name('recipes.destroy');
});

Route::controller(IngredientController::class)->group(function () {
    Route::get('/ingredients', 'index')->name('ingredients.index');
    Route::post('/ingredients', 'store')->name('ingredients.store');
    Route::get('/ingredients/{ingredient}', 'show')->name('ingredients.show');
    Route::delete('/ingredients/{ingredient}', 'destroy')->name('ingredients.destroy');
});

Route::controller(RecipeDetailController::class)->group(function () {
    Route::get('/recipeDetails', 'index')->name('recipeDetails.index');
    Route::post('/recipeDetails', 'store')->name('recipeDetails.store');
    Route::get('/recipeDetails/{recipeDetail}', 'show')->name('recipeDetails.show');
    Route::delete('/recipeDetails/{recipeDetail}', 'destroy')->name('recipeDetails.destroy');

});

Route::controller(DirectionController::class)->group(function () {
    Route::get('/directions', 'index')->name('directions.index');
    Route::post('/directions', 'store')->name('directions.store');
    Route::get('/directions/{direction}', 'show')->name('directions.show');
    Route::delete('/directions/{direction}', 'destroy')->name('directions.destroy');
});

Route::controller(UnitController::class)->group(function () {
    Route::get('/units', 'index')->name('units.index');
    Route::post('/units', 'store')->name('units.store');
    Route::get('/units/{unit}', 'show')->name('units.show');
    Route::delete('/units/{unit}', 'destroy')->name('units.destroy');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index')->name('users.index');
    Route::post('/users', 'store')->name('users.store');
    Route::get('/users/{user}', 'show')->name('users.show');
    Route::delete('/users/{user}', 'destroy')->name('users.destroy');
});
