<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests;

use PHPUnit\Framework\TestCase;
use Shopie\Mongoer\Provider\MongoDBOptions;
use Shopie\Mongoer\Tests\Stubs\ReadDbContextTest;

class SelectManyEntityTest extends TestCase
{
    public function testSelectManyEntity(): void
    {
        // create read context
        $ctx = new ReadDbContextTest();

        // configure
        $ctx->configure(new MongoDBOptions([
            'host' => getenv('DB_HOST'),
            'db' => getenv('DB_NAME')
        ]));

        // try connect
        if (!$ctx->connect()) {
            throw new \Exception('DB connection error');
        }

        // configure repos
        $ctx->configureRepositories();

        // select all properties
        $properties = $ctx->properties->find();

        // assert
        $this->assertNotNull($properties);
    }
}