<?php

namespace App\Models\Api;

use App\Models\Api\Concerns\HasIdentifier;
use App\Models\Api\Concerns\HasResource;
use App\Models\Api\Concerns\HasType;
use App\Models\Api\Contracts\JsonApiResource;
use Illuminate\Foundation\Auth\User as Authenticatable;

abstract class ApiUser extends Authenticatable implements JsonApiResource
{
    use HasIdentifier, HasResource, HasType;
}
