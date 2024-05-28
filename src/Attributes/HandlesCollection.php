<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class HandlesCollection
{
    public function __construct(private string $name = '')
    {
    }

    public function name(): string
    {
        return $this->name;
    }
}