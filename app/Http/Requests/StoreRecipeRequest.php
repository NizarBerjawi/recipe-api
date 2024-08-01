<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ForResource;
use App\Models\Recipe;
use Illuminate\Validation\Rule;

#[ForResource(Recipe::class)]
class StoreRecipeRequest extends JsonApiRequest
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
        $userRelation = $this->resource()->user()->getRelated();

        return [
            'data' => 'required',
            'data.type' => ['required', 'string', Rule::in([$this->resource()->getType()])],
            'data.attributes' => 'required',

            'data.attributes.name' => 'required|string|max:255',
            'data.attributes.description' => 'string|max:255',

            'data.relationships' => 'array:user',

            'data.relationships.user.data.type' => ['string', Rule::in([$userRelation->getType()])],
            'data.relationships.user.data.id' => [
                'uuid',
                'regex:/'.$this->user()->getKey().'$/',
                Rule::exists($userRelation->getTable(), $userRelation->getKeyName()),
            ],
        ];
    }
}
