<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests;

use PHPUnit\Framework\TestCase;
use Shopie\Mongoer\Provider\MongoDBOptions;
use Shopie\Mongoer\Tests\Stubs\EntityOfficeTest;
use Shopie\Mongoer\Tests\Stubs\ReadDbContextTest;

class SelectOneEntityTest extends TestCase
{
    public function testSelectOneEntity(): void
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

        // select a property
        //$entity = $ctx->properties->findById('662681a2cd41d499df0b2072');

        // select a user
        //$entity = $ctx->users->findOne(['email' => 'test.user@test.com']);

        // select an office
        $entity = $ctx->offices->findById('664da63a73092233f9089162');

        // assert
        $this->assertNotNull($entity);
        //$this->assertInstanceOf(EntityPropertyTest::class, $entity);
        $this->assertInstanceOf(EntityOfficeTest::class, $entity);
    }
}