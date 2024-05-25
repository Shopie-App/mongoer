<?php

declare(strict_types=1);

namespace Shopie\Mongoer\EntityBuilder;

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Model\BSONDocument;
use Shopie\Mongoer\Attributes\DocumentIndexKey;
use Shopie\Mongoer\Attributes\DocumentKey;
use Shopie\Mongoer\Entity;
use ReflectionClass;
use ReflectionProperty;

class EntityBuilderEntity
{
    public function __construct()
    {
    }

    public function adapt(BSONDocument $document, string $entityNs): Entity
    {
        // reflect entity class
        $reflector = new ReflectionClass($entityNs);

        // create new object
        /** @var Entity $entity */
        $entity = $reflector->newInstance();

        // get all private properties
        $properties = $reflector->getProperties(ReflectionProperty::IS_PRIVATE);

        // handle index key
        $this->setIndexPropertyValue($properties, $document, $entity);

        // handle all other properties
        foreach ($properties as $prop) {

            $this->setPropertyValue($prop, $document, $entity);
        }

        return $entity;
    }

    private function setIndexPropertyValue(array &$properties, BSONDocument $document, Entity $entity): void
    {
        $name = '_id';

        $pos = 0;

        /** @var ReflectionProperty $property */
        $property = null;

        $attributes = [];

        /** @var ReflectionProperty $prop */
        foreach ($properties as $prop) {

            $attributes = $prop->getAttributes(DocumentIndexKey::class);

            if (!empty($attributes)) {

                $property = $prop;
                break;
            }

            $pos++;
        }

        /** @var ReflectionAttribute $attribute */
        $attribute = $attributes[0];

        /** @var DocumentIndexKey $indexKey */
        $indexKey = $attribute->newInstance();

        if ($indexKey->name() != '') {
            $name = $indexKey->name();
        }

        $property->setValue($entity, $document[$name]);

        array_splice($properties, $pos, 1);
    }

    private function setPropertyValue(ReflectionProperty $property, BSONDocument $document, Entity $entity): void
    {
        $keyName = $property->getName();

        // get attribute
        $attributes = $property->getAttributes(DocumentKey::class);

        if (empty($attributes)) {
            return;
        }

        /** @var ReflectionAttribute $attribute */
        $attribute = $attributes[0];

        /** @var DocumentKey $key */
        $key = $attribute->newInstance();

        if ($key->name() != '') {
            $keyName = $key->name();
        }

        // no key set default value according to type
        if (!isset($document[$keyName])) {

            $property->setValue($entity, $this->initializeValue($property));
            return;
        }

        // handle object id
        if ($document[$keyName] instanceof (ObjectId::class)) {

            $property->setValue($entity, $document[$keyName]);
            return;
        }

        // handle datetime
        if ($document[$keyName] instanceof (UTCDateTime::class)) {

            $property->setValue($entity, $document[$keyName]->toDateTime());
            return;
        }

        // handle objects
        if (is_object($document[$keyName])) {

            $property->setValue($entity, $document[$keyName]->getArrayCopy());
            return;
        }

        $property->setValue($entity, $document[$keyName]);
    }

    private function initializeValue(ReflectionProperty $property): mixed
    {
        $val = 0;

        // handle built-in
        if ($property->getType()->isBuiltin()) {

            switch ($property->getType()->getName()) {
                case 'string': $val = '';
                    break;
                case 'array': $val = [];
                    break;
            }

            return $val;
        }

        // handle objects
        return new ($property->getType()->getName());
    }
}