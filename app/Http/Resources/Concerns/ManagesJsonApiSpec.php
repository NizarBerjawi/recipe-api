<?php

namespace App\Http\Resources\Concerns;

use App\Http\Resources\RelationshipCollection;
use App\Http\Resources\RelationshipResource;
use App\Models\Api\Contracts\JsonApiResource;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Spatie\QueryBuilder\QueryBuilderRequest;

trait ManagesJsonApiSpec
{
    /**
     * @var \Illuminate\Support\Collection<string, \Illuminate\Http\Resources\Json\JsonResource>
     */
    private $includes;

    /**
     * Determine whether we should append the 'included' attribute
     * to the resource response
     */
    public function showIncludes(): bool
    {
        $request = App::make(QueryBuilderRequest::class);

        return $request->includes()->isNotEmpty();
    }

    /**
     * Determine whether we should append the 'included' attribute
     * to the resource response
     */
    public function showRelationships(): bool
    {
        return $this->showIncludes();
    }

    /**
     * Adds included relationships to a resource
     *
     * @param  array<string|int, string>  $requestedRelations
     * @return \Illuminate\Http\Resources\MissingValue|mixed
     */
    public function collectRelationships(array $requestedRelations)
    {
        if (! $this->showRelationships()) {
            return $this->when(false, null);
        }

        $model = $this->resource;

        if (! $model instanceof Model) {
            throw new Exception('Relations can only be collected for a resource of type: '.Model::class);
        }

        $relationships = Collection::make();
        foreach ($requestedRelations as $alias => $relationship) {
            if (! $model->relationLoaded($relationship)) {
                continue;
            }

            $relation = $model->getRelation($relationship);

            $relationName = is_string($alias) ? $alias : $relationship;

            if (! $relation) {
                $relationResource = RelationshipResource::make(null);

                $relationships->put($relationName, $relationResource);

                continue;
            }

            if ($relation instanceof Model) {
                $relationships->put(
                    $relationName,
                    RelationshipResource::make($this->whenLoaded($relationship))
                );
            }

            if ($relation instanceof Collection) {
                $relationships->put(
                    $relationName,
                    RelationshipCollection::make($this->whenLoaded($relationship))
                );
            }
        }

        if ($relationships->isEmpty()) {
            return $this->when(false, null);
        }

        return $this->when($this->showRelationships(), fn () => $relationships);
    }

    /**
     * Adds includes to a resource
     *
     * @param  array<string|int, string>  $requestedIncludes
     * @return array<string, JsonResource>|array<mixed>
     */
    public function collectIncludes(array $requestedIncludes): array
    {
        if (! $this->showIncludes()) {
            return [];
        }

        /** @var \Illuminate\Support\Collection<int, JsonResource> */
        $resources = $this instanceof ResourceCollection
            ? $this->collection
            : Collection::make()->push($this);

        $this->includes = Collection::make();
        foreach ($resources as $resource) {
            /** @var Model */
            $model = $resource->resource;

            foreach ($requestedIncludes as $requestedInclude) {

                $relationshipChain = Str::of($requestedInclude)->explode('.');

                $this->recursivelyProcessIncludes($relationshipChain, $model);
            }
        }

        return [
            'included' => $this->includes->values(),
        ];
    }

    /**
     * Add the pagination information to the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, array<string, mixed>
     */
    public function paginationInformation($request, $paginated, $default): array
    {
        return [
            'links' => Arr::get($default, 'links'),
            'meta' => [
                "currentPage" => Arr::get($default, 'meta.current_page'),
                "from" => Arr::get($default, 'meta.from'),
                "lastPage" => Arr::get($default, 'meta.last_page'),
                "links" => Arr::get($default, 'meta.links'),
                "path" => Arr::get($default, 'meta.path'),
                "perPage" => Arr::get($default, 'meta.per_page'),
                "to" => Arr::get($default, 'meta.to'),
                "total" => Arr::get($default, 'meta.total'),
            ],
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<int, string>  $relationshipChain
     */
    protected function recursivelyProcessIncludes(Collection $relationshipChain, Model $model): void
    {
        // We pull out the first relation in the "chain" of nested relationships
        $relationName = $relationshipChain->pull(0);

        // If a name does not exist, it means we've already processed the deepest nested
        // relation in the "chain" and its time to exit the recursive function
        if (! $relationName) {
            return;
        }

        if (! $model->relationLoaded($relationName)) {
            return;
        }

        $relationValue = $model->getRelation($relationName);

        if (! $relationValue) {
            return;
        }

        if ($relationValue instanceof Collection && $relationValue->isEmpty()) {
            return;
        }

        $relationshipChain = $relationshipChain->values();

        if ($relationValue instanceof Model) {
            $this->addToIncludes($relationValue);

            $this->recursivelyProcessIncludes($relationshipChain, $relationValue);
        }

        if ($relationValue instanceof Collection) {
            $relationValue->each(function (Model $item) use ($relationshipChain) {
                $this->addToIncludes($item);

                $this->recursivelyProcessIncludes($relationshipChain, $item);
            });
        }
    }

    /**
     * Adds a resource to the "includes" collection
     */
    protected function addToIncludes(Model $resource): void
    {
        if (! $resource instanceof JsonApiResource) {
            throw new InvalidArgumentException(
                sprintf('Resource must implement: %s', JsonApiResource::class)
            );
        }

        $this->includes->put($resource->getIdentifier(), $resource->getResource());
    }
}
