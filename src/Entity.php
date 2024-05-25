<?php

declare(strict_types=1);

namespace Shopie\Mongoer;

use MongoDB\BSON\Document;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Persistable;
use Shopie\Mongoer\EntityBuilder\EntityBuilder;

abstract class Entity implements Persistable
{
    abstract public function id(): ObjectId;

    abstract public function setId(ObjectId $id): void;

    public function bsonSerialize(): Document
    {
        return (new EntityBuilder())->fromEntity($this);
    }

    public function bsonUnserialize(array $data): void
    {
    }
}