<?php

namespace App\Models;

use App\Models\Api\ApiModel;
use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

#[ScopedBy([UserScope::class])]
class RecipeDetail extends ApiModel
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
        'prep_time',
        'cook_time',
        'servings',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'prep_time' => 'integer',
            'cook_time' => 'integer',
            'servings' => 'integer',
        ];
    }

    /**
     * Get the recipe that owns the recipe detail.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Get the user that owns the recipe direction.
     */
    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Recipe::class, 'uuid', 'uuid', 'recipe_uuid', 'user_uuid');
    }

    /**
     * Get the Recipe Details created by a specific User
     */
    public function scopeByUser(Builder $query, User $user)
    {
        $columns = (new self)
            ->qualifyColumns(
                DB::getSchemaBuilder()->getColumnListing($this->getTable())
            );

        return $query
            ->select($columns)
            ->distinct(sprintf('%s.%s', $this->getTable(), $this->getKeyName()))
            ->join('recipes', 'recipes.uuid', '=', 'recipe_details.recipe_uuid')
            ->where('recipes.user_uuid', '=', $user->getKey());
    }
}
