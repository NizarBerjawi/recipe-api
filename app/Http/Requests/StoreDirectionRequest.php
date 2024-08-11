<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ForResource;
use App\Models\Direction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

#[ForResource(Direction::class)]
class StoreDirectionRequest extends JsonApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $resource = $this->resource();

        $recipeRelation = $resource->recipe()->getRelated();

        return [
            'data' => 'required',
            'data.type' => 'required|string|in:'.$resource->getType(),
            'data.attributes' => 'required',
            'data.attributes.direction' => 'required|string|max:255',
            'data.attributes.order' => 'required|integer',

            'data.relationships' => 'array:recipe',
            'data.relationships.recipe' => 'required',
            'data.relationships.recipe.data.type' => 'required|string|in:'.$recipeRelation->getType(),
            'data.relationships.recipe.data.id' => [
                'required',
                'uuid',
                Rule::exists($recipeRelation->getTable(), $recipeRelation->getKeyName())
                    ->where('user_uuid', $this->user()->getKey()),
            ],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (! $this->validOrder()) {
                    $validator->errors()->add(
                        'data.attributes.order',
                        'The data.attributes.order has already been taken....'
                    );
                }
            },
        ];
    }

    /**
     * Check if the provided Direction order is not already taken
     * for this recipe.
     */
    protected function validOrder()
    {
        return DB::table($this->resource()->getTable())
            ->where('recipe_uuid', $this->input('data.relationships.recipe.data.id'))
            ->where('order', $this->input('data.attributes.order'))
            ->doesntExist();
    }
}
