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
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;

#[ScopedBy([UserScope::class])]
class Ingredient extends ApiModel
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
        'quantity',
        'name',
        'display_text',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'float',
        ];
    }

    /**
     * Get the recipe associated with this ingredient.
     */
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class)->distinct('recipe_uuid');
    }

    /**
     * Get the unit associated with this ingredient.
     */
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'ingredient_recipe');
    }

    /**
     * Get the user that owns the recipe direction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDisplay()
    {
        return Blade::render($this->display_text, [
            'quantity' => $this->quantity,
            'name' => $this->name,
        ], true);
    }

    public function scopeByUser(Builder $query, User $user)
    {
        $columns = (new self)
            ->qualifyColumns(
                DB::getSchemaBuilder()->getColumnListing($this->getTable())
            );

        return $query
            ->select($columns)
            ->distinct(sprintf('%s.%s', $this->getTable(), $this->getKeyName()))
            ->join('recipes', 'recipes.uuid', '=', 'ingredients.recipe_uuid')
            ->where('recipes.user_uuid', '=', $user->getKey());
    }
}
