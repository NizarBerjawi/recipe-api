<?php

namespace App\Models\Api\Concerns;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Resources\Json\JsonResource;
use ReflectionClass;

trait HasResource
{
    /**
     * Returns the Spatie QueryBuilder which allows us
     * to add filters, sorts, includes, and fields to our
     * query.
     */
    public function getResource(): JsonResource
    {
        $reflection = new ReflectionClass(static::class);

        $name = $reflection->getShortName();

        $filesystem = new Filesystem();

        $resourceName = "{$name}Resource";

        $resourcePath = app_path("Http/Resources/$name/$resourceName.php");

        if ($filesystem->missing($resourcePath)) {
            throw new Exception("File '$resourcePath' was not found.");
        }

        $namespace = "\\App\\Http\\Resources\\$name\\$resourceName";

        return new $namespace($this);
    }
}
