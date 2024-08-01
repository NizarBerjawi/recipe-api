<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ForResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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
}
