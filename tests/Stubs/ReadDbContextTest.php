<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests\Stubs;

use Shopie\Mongoer\DbContext;

class ReadDbContextTest extends DbContext
{
    public PropertyRepositoryTest $properties;

    public UserRepositoryTest $users;

    public OfficeRepositoryTest $offices;

    public function __construct()
    {
    }
}