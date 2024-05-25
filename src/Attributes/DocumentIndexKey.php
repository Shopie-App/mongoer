<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class DocumentIndexKey extends DocumentKey
{
    public function __construct(string $name = '')
    {
        parent::__construct($name);
    }
}