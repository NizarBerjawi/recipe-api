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
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy([UserScope::class])]
class Recipe extends ApiModel
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
        'name',
        'description',
    ];

    /**
     * Get the user that owns the recipe.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the details associated with the recipe.
     */
    public function recipeDetail(): HasOne
    {
        return $this->hasOne(RecipeDetail::class);
    }

    /**
     * Get the directions for the recipe.
     */
    public function directions(): HasMany
    {
        return $this->hasMany(Direction::class);
    }

    /**
     * Get the ingredients associated with this recipe.
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class);
    }

    /**
     * Get the units used in this recipe.
     */
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'ingredient_recipe');
    }

    /**
     * Get the Ingredients created by a specific User
     */
    public function scopeByUser(Builder $query, User $user): Builder
    {
        return $query->where('recipes.user_uuid', $user->getKey());
    }
}
