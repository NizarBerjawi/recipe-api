<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ForResource;
use App\Models\Recipe;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

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
        $possibleRelations = Collection::make([
            'recipeDetail',
            'directions',
            'ingredients',
        ]);

        return [
            'data' => 'required',
            'data.type' => ['required', 'string', Rule::in([$this->resource()->getType()])],
            'data.attributes' => 'required',
            'data.attributes.name' => 'required|string|max:255',
            'data.attributes.description' => 'string|max:255',

            ...$this->relationshipRules($possibleRelations),
        ];
    }
}
