<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests;

use PHPUnit\Framework\TestCase;
use Shopie\Mongoer\Provider\MongoDBOptions;
use Shopie\Mongoer\Tests\Stubs\EntityPropertyTest;
use Shopie\Mongoer\Tests\Stubs\ReadDbContextTest;

class InsertOneEntityTest extends TestCase
{
    public function testInsertOneEntity(): void
    {
        // create read context
        $ctx = new ReadDbContextTest();

        // configure
        $ctx->configure(new MongoDBOptions([
            'host' => getenv('DB_HOST'),
            'db' => getenv('DB_NAME')
        ]));

        // try connect
        $result = $ctx->connect();

        // configure repos
        $ctx->configureRepositories();

        // create entity
        $property = new EntityPropertyTest();
        $property->setApiId(54381);

        // insert
        $property = $ctx->properties->add($property);

        // assert
        $this->assertNotNull($property);
    }
}