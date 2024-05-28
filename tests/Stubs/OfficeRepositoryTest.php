<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests\Stubs;

use Shopie\Mongoer\Attributes\HandlesCollection;
use Shopie\Mongoer\Repository;

#[HandlesCollection('app_offices')]
class OfficeRepositoryTest extends Repository
{
    public function __construct()
    {
        $this->setEntity(EntityOfficeTest::class);
    }
}