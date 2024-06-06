<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests;

use PHPUnit\Framework\TestCase;
use Shopie\Mongoer\Provider\MongoDBOptions;
use Shopie\Mongoer\Tests\Stubs\ReadDbContextTest;

class DeleteOneEntityTest extends TestCase
{
    public function testDeleteOneEntity(): void
    {
        // create read context
        $ctx = new ReadDbContextTest();

        // configure
        $ctx->configure(new MongoDBOptions([
            'host' => getenv('DB_HOST'),
            'db' => getenv('DB_NAME')
        ]));

        // try connect
        $ctx->connect();

        // configure repos
        $ctx->configureRepositories();

        // delete
        $result = $ctx->users->deleteById('666199006512c3c6598ef73e');

        // assert
        $this->assertTrue($result);
    }
}