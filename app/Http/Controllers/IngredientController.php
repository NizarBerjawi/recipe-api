<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIngredientRequest;
use App\Http\Resources\Ingredient\IngredientCollection;
use App\Http\Resources\Ingredient\IngredientResource;
use App\Models\Ingredient;
use App\Queries\IngredientQuery;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

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
     * Store a newly created resource in storage.
     */
    public function store(StoreIngredientRequest $request, IngredientQuery $query)
    {
        DB::beginTransaction();

        try {
            $ingredient = new Ingredient($request->input('data.attributes'));

            if ($request->hasRelationship('user')) {
                $data = $request->getRelationship('user');

                $ingredient->user()->associate($data->get('id'));
            }

            if ($request->hasRelationship('recipe')) {
                $data = $request->getRelationship('recipe');

                $ingredient->recipes()->attach($data->get('id'));
            }

            $ingredient->save();

            $ingredient = $query->builder()
                ->where('ingredient.uuid', $ingredient->getKey())
                ->first();

            return IngredientResource::make($ingredient)
                ->response()
                ->setStatusCode(Response::HTTP_CREATED)
                ->withHeaders([
                    'Location' => route('ingredients.show', $ingredient->getKey()),
                ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
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
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
