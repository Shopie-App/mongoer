<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests\Stubs;

use Shopie\Mongoer\Repository;

class OfficeRepositoryTest extends Repository
{
    public function __construct()
    {
        $this->setEntity(EntityOfficeTest::class);
    }
}