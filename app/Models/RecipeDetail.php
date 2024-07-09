<?php

namespace App\Models;

use App\Models\Api\ApiModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeDetail extends ApiModel
{
    use HasFactory, HasUuids;

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
            'servings' => 'integer'
        ];
    }

    /**
     * Get the recpie that owns the recipe detail.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
