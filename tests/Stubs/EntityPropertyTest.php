<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests\Stubs;

use MongoDB\BSON\ObjectId;
use Shopie\Mongoer\Attributes\DocumentIndexKey;
use Shopie\Mongoer\Attributes\DocumentKey;
use Shopie\Mongoer\Entity;

class EntityPropertyTest extends Entity
{
    #[DocumentIndexKey]
    private ObjectId $id;

    #[DocumentKey('api_id')]
    private int $apiId;

    public function __construct()
    {
    }

    public function id(): ObjectId
    {
        return $this->id;
    }

    public function apiId(): int
    {
        return $this->apiId;
    }

    public function setId(ObjectId $id): void
    {
        $this->id = $id;
    }

    public function setApiId(int $apiId): void
    {
        $this->apiId = $apiId;
    }
}