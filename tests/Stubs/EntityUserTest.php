<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests\Stubs;

use DateTime;
use MongoDB\BSON\ObjectId;
use Shopie\Mongoer\Attributes\DocumentIndexKey;
use Shopie\Mongoer\Attributes\DocumentKey;
use Shopie\Mongoer\Entity;

class EntityUserTest extends Entity
{
    #[DocumentIndexKey]
    private ObjectId $id;

    #[DocumentKey]
    private string $password;

    #[DocumentKey]
    private string $group;

    #[DocumentKey('created_at')]
    private DateTime $createdAt;

    public function __construct()
    {
    }

    public function id(): ObjectId
    {
        return $this->id;
    }

    public function group(): string
    {
        return $this->group;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setId(ObjectId $id): void
    {
        $this->id = $id;
    }

    public function setGroup(string $group): void
    {
        $this->group = $group;
    }
}