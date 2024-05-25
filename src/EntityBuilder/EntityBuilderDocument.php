<?php

declare(strict_types=1);

namespace Shopie\Mongoer\EntityBuilder;

use MongoDB\BSON\Document;
use MongoDB\BSON\UTCDateTime;
use Shopie\Mongoer\Attributes\DocumentIndexKey;
use Shopie\Mongoer\Attributes\DocumentKey;
use Shopie\Mongoer\Entity;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;

class EntityBuilderDocument
{
    public function __construct()
    {
    }

    public function adapt(Entity $entity): Document
    {
        // array from entity
        $objectArray = [];

        // reflect class
        $reflector = new ReflectionClass($entity);

        // get all private properties
        $properties = $reflector->getProperties(ReflectionProperty::IS_PRIVATE);

        /** @var ReflectionProperty $prop */
        foreach ($properties as $prop) {

            // get key attribute
            $attributes = $prop->getAttributes();

            // default if no attribute
            if (empty($attributes)) {

                $objectArray[$prop->getName()] = $prop->getValue($entity);
                continue;
            }

            // skip index key
            if ($this->isIndexKey($attributes)) {
                continue;
            }

            // skip not initialized
            if (!$prop->isInitialized($entity)) {
                continue;
            }

            // get key name
            if (($keyName = $this->getKeyName($attributes)) == '') {
                $keyName = $prop->getName();
            }

            // handle datetime
            if ($prop->getType()->getName() === \DateTime::class) {

                /** @var \DateTime $dateTime */
                $dateTime = $prop->getValue($entity);

                $objectArray[$keyName] = new UTCDateTime($dateTime->getTimestamp() * 1000);
                continue;
            }

            // add to array
            $objectArray[$keyName] = $prop->getValue($entity);

        }

        // return BSON document
        return Document::fromPHP($objectArray);
    }

    private function isIndexKey(array $attributes): bool
    {
        $isKey = false;

        /** @var ReflectionAttribute $attr */
        foreach ($attributes as $attr) {

            if ($attr->getName() === DocumentIndexKey::class) {
                $isKey = true;
                break;
            }
        }

        return $isKey;
    }

    private function getKeyName(array $attributes): string
    {
        /** @var DocumentKey $key */
        $key = null;

        /** @var ReflectionAttribute $attr */
        foreach ($attributes as $attr) {

            if ($attr->getName() === DocumentKey::class) {

                $key = $attr->newInstance();
                break;
            }
        }

        if ($key === null) {
            return '';
        }

        if ($key->name() == '') {
            return '';
        }

        return $key->name();
    }
}