<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ForResource;
use App\Models\Direction;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeDetail;
use Closure;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

#[ForResource(Recipe::class)]
class StoreRecipeRequest extends JsonApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $recipeDetailRelation = $this->resource()->recipeDetail()->getRelated();
        $directionsRelation = $this->resource()->directions()->getRelated();
        $ingredientsRelation = $this->resource()->ingredients()->getRelated();

        return [
            'data' => 'required',
            'data.type' => ['required', 'string', Rule::in([$this->resource()->getType()])],
            'data.attributes' => 'required',
            'data.attributes.name' => 'required|string|max:255',
            'data.attributes.description' => 'string|max:255',

            'data.relationships' => 'array:recipeDetail,directions,ingredients',

            'data.relationships.recipeDetail.data.type' => ['string', Rule::in([$recipeDetailRelation->getType()])],
            'data.relationships.recipeDetail.data.id' => [
                'uuid',
                function (string $attribute, mixed $value, Closure $fail) use ($recipeDetailRelation) {
                    $validId = RecipeDetail::query()
                        ->byUser($this->user())
                        ->where($recipeDetailRelation->getKeyName(), $value)
                        ->exists();

                    if (! $validId) {
                        $fail("The {$attribute} is invalid.");
                    }
                },
            ],

            'data.relationships.directions.data.*.type' => ['string', Rule::in([$directionsRelation->getType()])],
            'data.relationships.directions.data.*.id' => [
                'uuid',
                function (string $attribute, mixed $value, Closure $fail) use ($directionsRelation) {
                    $validId = Direction::query()
                        ->byUser($this->user())
                        ->where($directionsRelation->getKeyName(), $value)
                        ->exists();

                    if (! $validId) {
                        $fail("The {$attribute} is invalid.");
                    }
                },
            ],

            'data.relationships.ingredients.data.*.type' => ['string', Rule::in([$ingredientsRelation->getType()])],
            'data.relationships.ingredients.data.*.id' => [
                'uuid',
                function (string $attribute, mixed $value, Closure $fail) use ($ingredientsRelation) {
                    $validId = Ingredient::query()
                        ->byUser($this->user())
                        ->where($ingredientsRelation->getKeyName(), $value)
                        ->exists();

                    if (! $validId) {
                        $fail("The {$attribute} is invalid.");
                    }
                },
            ],
        ];
    }

    public function relationshipRules(Collection $relations = []): array
    {
        $rules = [
            'data.relationships' => 'array:' . implode(',', $relations->all())
        ];

        $relations->map(function(string $relation) {
            /** @var \Illuminate\Database\Eloquent\Relations\Relation */
            $relation = $this->resource()->$relation();
            
            /** @var \Illuminate\Database\Eloquent\Model */
            $model = $relation->getRelated();

            return match(true) {
                $relation instanceof BelongsTo => [
                    "data.relationships.$relation.data.type" => ['string', Rule::in([$model->getType()])],
                    "data.relationships.$relation.data.id" => [
                        'uuid',
                        function (string $attribute, mixed $value, Closure $fail) use ($model) {
                            $validId = $model
                                ->query()
                                ->byUser($this->user())
                                ->where($model->getKeyName(), $value)
                                ->exists();
    
                            if (! $validId) {
                                $fail("The {$attribute} is invalid.");
                            }
                        },
                    ]
                    ],
                $relation instanceof HasMany => [
                    "data.relationships.$relation.data.type" => ['string', Rule::in([$model->getType()])],
                    "data.relationships.$relation.data.id" => [
                        'uuid',
                        function (string $attribute, mixed $value, Closure $fail) use ($model) {
                            $validId = $model
                                ->query()
                                ->byUser($this->user())
                                ->where($model->getKeyName(), $value)
                                ->exists();
    
                            if (! $validId) {
                                $fail("The {$attribute} is invalid.");
                            }
                        },
                    ]
                ]
            };
        });

        return $rules;
    }
}
