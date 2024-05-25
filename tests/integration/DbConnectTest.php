<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests;

use PHPUnit\Framework\TestCase;
use Shopie\Mongoer\Provider\MongoDBOptions;
use Shopie\Mongoer\Tests\Stubs\ReadDbContextTest;

class DbConnectTest extends TestCase
{
    public function testDbConnect(): void
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

        // assert
        $this->assertTrue($result);
    }
}