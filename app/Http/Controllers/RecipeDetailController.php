<?php

namespace App\Http\Controllers;

use App\Http\Resources\Recipe\RecipeResource;
use App\Http\Resources\RecipeDetail\RecipeDetailCollection;
use App\Models\RecipeDetail;
use App\Queries\RecipeDetailQuery;
use Illuminate\Http\Request;

class RecipeDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RecipeDetailQuery $query)
    {
        return RecipeDetailCollection::make(
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
    public function show(RecipeDetailQuery $query, RecipeDetail $recipeDetail)
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
