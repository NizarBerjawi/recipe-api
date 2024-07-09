<?php

namespace App\Http\Controllers;

use App\Http\Resources\Recipe\RecipeCollection;
use App\Http\Resources\Recipe\RecipeResource;
use App\Models\Recipe;
use App\Queries\RecipeQuery;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RecipeQuery $query)
    {
        return RecipeCollection::make(
            $query->builder()->jsonPaginate()
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RecipeQuery $query, Recipe $recipe)
    {
        return RecipeResource::make(
            $query->builder()->first()
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
