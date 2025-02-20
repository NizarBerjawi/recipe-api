<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;

class ValidRelationshipId implements ValidationRule
{
    public function __construct(protected Model $baseModel, protected Model $relationModel) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validId = $this->relationModel
            ->query()
            ->byUser($this->baseModel)
            ->where($this->relationModel->getKeyName(), $value)
            ->exists();

        if (! $validId) {
            $fail("The {$attribute} is not a valid relation.");
        }
    }
}
