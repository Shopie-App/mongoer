<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests\Stubs;

use Shopie\Mongoer\Attributes\HandlesCollection;
use Shopie\Mongoer\Repository;

#[HandlesCollection('app_users')]
class UserRepositoryTest extends Repository
{
    public function __construct()
    {
        $this->setEntity(EntityUserTest::class);
    }
}