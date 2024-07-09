<?php

namespace App\Http\Controllers;

use App\Http\Resources\Direction\DirectionCollection;
use App\Http\Resources\Direction\DirectionResource;
use App\Models\Direction;
use App\Queries\DirectionQuery;
use Illuminate\Http\Request;

class DirectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DirectionQuery $query)
    {
        return DirectionCollection::make(
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
    public function show(DirectionQuery $query, Direction $direction)
    {
        return DirectionResource::make(
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
