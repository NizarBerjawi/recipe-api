<?php

namespace App\Models\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Scope;
use Symfony\Component\HttpFoundation\Exception\UnexpectedValueException;

class UserRelationScope implements Scope
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

        if (!method_exists($model, 'user')) {
            throw new UnexpectedValueException('Expected model to have a \'user\' relation');
        }

        if (!$model->user() instanceof Relation) {
            throw new UnexpectedValueException('Expected \'user\' relation to be instance of: ' . Relation::class);
        }
                
        /** @var \App\Models\User|null */
        $user = request()->user();

        $builder->whereHas('user', fn (Builder $query) => $query->where($user->getQualifiedKeyName(), $user->getKey()));
    }
}
