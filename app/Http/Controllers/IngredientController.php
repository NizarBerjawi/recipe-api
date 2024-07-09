<?php

namespace App\Http\Controllers;

use App\Http\Resources\Ingredient\IngredientCollection;
use App\Http\Resources\Ingredient\IngredientResource;
use App\Models\Ingredient;
use App\Queries\IngredientQuery;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IngredientQuery $query)
    {
        return IngredientCollection::make(
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
    public function show(IngredientQuery $query, Ingredient $ingredient)
    {
        return IngredientResource::make(
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
