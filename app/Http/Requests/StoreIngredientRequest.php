<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ForResource;
use App\Models\Ingredient;
use Illuminate\Validation\Rule;

#[ForResource(Ingredient::class)]
class StoreIngredientRequest extends JsonApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $resource = $this->resource();

        $userRelation = $resource->user()->getRelated();
        $recipeRelation = $resource->recipes()->getRelated();

        return [
            'data' => 'required',
            'data.type' => 'required|string|in:'.$resource->getType(),
            'data.attributes' => 'required',

            'data.attributes.name' => 'required|string|max:255',

            'data.relationships' => 'array:user,recipe',

            'data.relationships.user.data.type' => 'string|in:'.$userRelation->getType(),
            'data.relationships.user.data.id' => [
                'uuid',
                Rule::exists($userRelation->getTable(), $userRelation->getKeyName())
                    ->where('user_uuid', $this->user()->getKey())
                    ->where('user_uuid', $this->input('data.relationships.user.data.id')),
            ],

            'data.relationships.recipe.data.type' => 'required|string|in:'.$recipeRelation->getType(),
            'data.relationships.recipe.data.id' => [
                'required',
                'uuid',
                Rule::exists($recipeRelation->getTable(), $recipeRelation->getKeyName())
                    ->where('user_uuid', $this->user()->getKey()),
            ],
        ];
    }
}
