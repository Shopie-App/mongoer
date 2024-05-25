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
        $this->properties = new PropertyRepositoryTest();

        $this->users = new UserRepositoryTest();

        $this->offices = new OfficeRepositoryTest();
    }

    public function configureRepositories(): void
    {
        $this->properties->setCollection(parent::db()->selectCollection('properties'));

        $this->users->setCollection(parent::db()->selectCollection('app_users'));

        $this->offices->setCollection(parent::db()->selectCollection('app_offices'));
    }
}