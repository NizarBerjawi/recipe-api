<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::controller(RecipeController::class)->group(function () {
    Route::get('/recipes', 'index');
});