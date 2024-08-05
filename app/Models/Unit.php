<?php

namespace App\Models;

use App\Models\Api\ApiModel;
use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Unit extends ApiModel
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'label',
        'type',
    ];

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_recipe')->distinct('ingredient_uuid');
    }

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'ingredient_recipe')->distinct('recipe_uuid');
    }

    public function scopeByUser(Builder $query, User $user)
    {
        $columns = (new Unit)
            ->qualifyColumns(
                DB::getSchemaBuilder()->getColumnListing('units')
            );

        return $query
            ->select($columns)
            ->distinct(sprintf('%s.%s', $this->getTable(), $this->getKeyName()))
            ->join('ingredient_recipe', 'ingredient_recipe.unit_uuid', '=', 'units.uuid')
            ->join('recipes', 'recipes.uuid', '=', 'ingredient_recipe.recipe_uuid')
            ->where('recipes.user_uuid', '=', $user->getKey());
    }
}
