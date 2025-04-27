<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Symfony\Component\HttpFoundation\Exception\UnexpectedValueException;

class UserScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $isApiRequest = ! empty(request()->bearerToken());

        if (! $isApiRequest) {
            return;
        }

        if (! method_exists($model, 'scopeByUser')) {
            throw new UnexpectedValueException('Expected model to have a local scope \'byUser\'.');
        }

        /** @var \App\Models\User|null */
        $user = request()->user();

        $builder->byUser($user);
    }
}
