<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests\Stubs;

use Shopie\Mongoer\Attributes\HandlesCollection;
use Shopie\Mongoer\Repository;

#[HandlesCollection('properties_norm')]
class PropertyRepositoryTest extends Repository
{
    public function __construct()
    {
        $this->setEntity(EntityPropertyTest::class);
    }
}