<?php

namespace App\Queries\Concerns;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class QueryFor
{
    public string $subject;

    public function __construct(string $subject)
    {
        $this->subject = $subject;
    }
}
