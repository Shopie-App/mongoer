<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests\Stubs;

use Shopie\Mongoer\Repository;

class UserRepositoryTest extends Repository
{
    public function __construct()
    {
        $this->setEntity(EntityUserTest::class);
    }
}