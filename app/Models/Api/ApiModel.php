<?php

namespace App\Models\Api;

use App\Models\Api\Concerns\HasIdentifier;
use App\Models\Api\Concerns\HasResource;
use App\Models\Api\Concerns\HasType;
use App\Models\Api\Contracts\JsonApiResource;
use Illuminate\Database\Eloquent\Model as LaravelModel;

abstract class ApiModel extends LaravelModel implements JsonApiResource
{
    use HasIdentifier, HasResource, HasType;
}
