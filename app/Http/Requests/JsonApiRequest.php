<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ForResource;
use App\Rules\ValidRelationshipId;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use ReflectionClass;

class JsonApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Return the "subject" for this query
     */
    public function resource()
    {
        $reflectionClass = new ReflectionClass(static::class);

        $attributes = $reflectionClass->getAttributes(ForResource::class);

        /** @var \ReflectionAttribute */
        $subject = Arr::first($attributes);

        /** @var string */
        $modelClass = Arr::first($subject->getArguments());

        return new $modelClass;
    }

    public function hasRelationships()
    {
        return $this->filled('data.relationships');
    }

    public function hasRelationship(string $relation)
    {
        return $this->filled("data.relationships.$relation");
    }

    public function getRelationship(string $relation): Collection
    {
        $data = $this->collect("data.relationships.$relation.data");

        if ($data->has(['type', 'id'])) {
            return $data;
        }

        return $data->unique('id');
    }

    /**
     * Generate rules for all the possible relationships
     */
    public function relationshipRules(Collection $relations): Collection
    {
        $defaultRule = Collection::make([
            'data.relationships' => 'array:'.$relations->implode(','),
        ]);

        $otherRules = $relations->map(function (string $requiredRelation) {
            /** @var \Illuminate\Database\Eloquent\Relations\Relation */
            $relation = $this->resource()->$requiredRelation();

            /** @var \Illuminate\Database\Eloquent\Model */
            $relationModel = $relation->getRelated();

            $typeRule = ['string', Rule::in([$relationModel->getType()])];
            $idRules = ['uuid', new ValidRelationshipId($this->user(), $relationModel)];

            return match (true) {
                $relation instanceof BelongsTo => [
                    "data.relationships.$requiredRelation.data.type" => $typeRule,
                    "data.relationships.$requiredRelation.data.id" => $idRules,
                ],
                $relation instanceof HasMany => [
                    "data.relationships.$requiredRelation.*.data.type" => $typeRule,
                    "data.relationships.$requiredRelation.*.data.id" => $idRules,
                ]
            };
        });

        return $defaultRule->merge($otherRules);
    }
}
