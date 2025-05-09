<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDirectionRequest;
use App\Http\Resources\Direction\DirectionCollection;
use App\Http\Resources\Direction\DirectionResource;
use App\Models\Direction;
use App\Queries\DirectionQuery;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

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
     * Store a newly created resource in storage.
     */
    public function store(StoreDirectionRequest $request, DirectionQuery $query)
    {
        DB::beginTransaction();

        try {
            $direction = new Direction($request->input('data.attributes'));

            if ($request->hasRelationship('recipe')) {
                $data = $request->getRelationship('recipe');

                $direction->recipe()->associate($data->get('id'));
            }

            $direction->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        $direction = $query->builder()
            ->where('directions.uuid', $direction->getKey())
            ->first();

        return DirectionResource::make($direction)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED)
            ->withHeaders([
                'Location' => route('directions.show', $direction->getKey()),
            ]);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Direction $direction)
    {
        $direction->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
