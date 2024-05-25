<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Contracts;

use Shopie\Mongoer\Entity;

interface CollectionInterface
{
    /**
     * Add an entity to the collection.
     * 
     * @param Entity $entity The entity.
     */
    public function add(Entity $entity): void;
}