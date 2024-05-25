<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests;

use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;
use Shopie\Mongoer\Provider\MongoDBOptions;
use Shopie\Mongoer\Tests\Stubs\EntityUserTest;
use Shopie\Mongoer\Tests\Stubs\ReadDbContextTest;

class UpdateOneEntityTest extends TestCase
{
    public function testUpdateOneEntity(): void
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

        // create entity
        $user = new EntityUserTest();

        $user->setId(new ObjectId('66435f3adcadc9b6420d7f13'));
        $user->setGroup('admin');

        // update
        $modifiedCount = $ctx->users->update($user);

        // assert
        // will be one if value is changed only
        $this->assertEquals(1, $modifiedCount);
    }
}