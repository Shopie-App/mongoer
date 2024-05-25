<?php

declare(strict_types=1);

namespace Shopie\Mongoer\EntityBuilder;

use MongoDB\BSON\Document;
use MongoDB\Model\BSONDocument;
use Shopie\Mongoer\Entity;

class EntityBuilder
{
    public function __construct()
    {
    }

    /**
     * Converts a BSON document to an entity.
     */
    public function fromDocument(BSONDocument $document, string $entityNs): Entity
    {
        return (new EntityBuilderEntity())->adapt($document, $entityNs);
    }

    /**
     * Converts an entity to a BSON document.
     */
    public function fromEntity(Entity $entity): Document
    {
        return (new EntityBuilderDocument())->adapt($entity);
    }
}