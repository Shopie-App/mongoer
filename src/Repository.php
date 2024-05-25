<?php

declare(strict_types=1);

namespace Shopie\Mongoer;

use MongoDB\BSON\Document;
use MongoDB\BSON\ObjectId;
use MongoDB\Collection;
use MongoDB\Model\BSONDocument;
use Shopie\Mongoer\EntityBuilder\EntityBuilder;

abstract class Repository
{
    protected string $entity;

    protected Collection $collection;

    private int $totalFound;

    public function setEntity(string $entityNs): void
    {
        $this->entity = $entityNs;
    }

    public function setCollection(Collection $collection): void
    {
        $this->collection = $collection;
    }

    /**
     * Estimated count of found documents from an executed find.
     */
    public function totalFound(): int
    {
        return $this->totalFound ?? 0;
    }

    /**
     * Estimated count of found documents without returned results.
     */
    public function count(array $filter = [], array $options = []): int
    {
        return $this->collection->countDocuments($filter, $options);
    }

    /**
     * Finds a document in the collection by id.
     */
    public function findById(string $id, array $options = []): ?Entity
    {
        if (($document = $this->collection->findOne(['_id' => new ObjectId($id)], $options)) === null) {
            return null;
        }

        return (new EntityBuilder())->fromDocument($document, $this->entity);
    }

    /**
     * Finds a document in the collection by seacrh filter.
     */
    public function findOne(array $filter = [], array $options = []): ?Entity
    {
        if (($document = $this->collection->findOne($filter, $options)) === null) {
            return null;
        }

        return (new EntityBuilder())->fromDocument($document, $this->entity);
    }

    /**
     * Finds a document with supplied criteria and options.
     */
    public function findOneRaw(array $filter = [], array $options = []): ?BSONDocument
    {
        return $this->collection->findOne($filter, $options);
    }

    /**
     * Finds many documents in the collection.
     */
    public function find(array $filter = [], array $options = []): ?EntityCollection
    {
        $cursor = $this->collection->find($filter, $options);
        
        if ($cursor->isDead()) {
            return null;
        }

        if (isset($options['limit']) && isset($options['skip'])) {
            $this->totalFound = $this->collection->countDocuments($filter);
        }

        $entities = new EntityCollection();

        foreach ($cursor as $document) {

            $entities->add(
                (new EntityBuilder())->fromDocument($document, $this->entity)
            );
        }

        return $entities;
    }

    /**
     * Executes an aggregation pipeline on the collection.
     */
    public function aggregate(array $pipeline = [], array $options = []): ?EntityCollection
    {
        $cursor = $this->collection->aggregate($pipeline, $options);
        
        if ($cursor->isDead()) {
            return null;
        }
        
        $entities = new EntityCollection();

        foreach ($cursor as $document) {

            $entities->add(
                (new EntityBuilder())->fromDocument($document, $this->entity)
            );
        }

        return $entities;
    }

    /**
     * Executes an aggregation pipeline on the collection and returns raw documents.
     */
    public function aggregateRaw(array $pipeline = [], array $options = []): ?array
    {
        $cursor = $this->collection->aggregate($pipeline, $options);
        
        if ($cursor->isDead()) {
            return null;
        }
        
        $documents = [];

        /** @var BSONDocument $document */
        foreach ($cursor as $document) {

            $documents[] = $document->getArrayCopy();
        }

        return $documents;
    }

    /**
     * Adds a document to the collection.
     */
    public function add(Entity $entity): Entity
    {
        $result = $this->collection->insertOne($entity->bsonSerialize());

        $entity->setId($result->getInsertedId());

        return $entity;
    }

    /**
     * Adds a raw array to the collection.
     */
    public function addRaw(array $document): mixed
    {
        $result = $this->collection->insertOne($document);

        return $result->getInsertedId();
    }

    /**
     * Fully replaces a document.
     */
    public function replaceRaw(ObjectId $id, array|Document $document): bool
    {
        $result = $this->collection->replaceOne(['_id' => $id], $document);
        
        return $result->isAcknowledged();
    }

    /**
     * Updates a document.
     */
    public function update(Entity $entity): int
    {
        $result = $this->collection->updateOne(['_id' => $entity->id()], [
            '$set' => $entity->bsonSerialize()
        ]);

        if (!$result->isAcknowledged()) {
            return 0;
        }

        return $result->getModifiedCount();
    }
}