<?php

namespace App\Queries\Concerns;

class SubjectOf
{
    public string $subject;

    public function __construct(string $subject)
    {
        $this->subject = $subject;
    }
}