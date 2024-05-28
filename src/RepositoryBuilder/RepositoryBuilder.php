<?php

declare(strict_types=1);

namespace Shopie\Mongoer\RepositoryBuilder;

use ReflectionClass;
use ReflectionProperty;
use Shopie\Mongoer\Attributes\HandlesCollection;
use Shopie\Mongoer\DbContext;
use Shopie\Mongoer\Exceptions\RepositoryCollectionNoNameException;
use Shopie\Mongoer\Exceptions\RepositoryCollectionNullException;
use Shopie\Mongoer\Repository;

class RepositoryBuilder
{
    public function __construct()
    {
    }

    public function build(DbContext $dbContext): void
    {
        $reflector = new ReflectionClass($dbContext);

        $properties = $reflector->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {

            // repository class type
            $type = $property->getType()->getName();

            // if it extends repository base class, its a repo
            if (!is_subclass_of($type, Repository::class)) {
                continue;
            }

            //repository instance
            $repoInstance = new ($property->getType()->getName());

            // create new repository instance
            $property->setValue($dbContext, $repoInstance);

            // reflect the repository object
            $propReflector = new ReflectionClass($repoInstance);

            // get attributes, to find out which collection it handles
            $attrs = $propReflector->getAttributes(HandlesCollection::class);

            // if empty cannot continue
            if (empty($attrs)) {
                throw new RepositoryCollectionNullException($type);
            }

            // init attribute to get collection name
            /** @var HandlesCollection $handler */
            $handler = $attrs[0]->newInstance();

            // no name, stop
            if ($handler->name() == '') {
                throw new RepositoryCollectionNoNameException($type);
            }

            // invoke repository's set collection method
            $propReflector->getMethod('setCollection')
            ->invoke($repoInstance, $dbContext->db()->selectCollection($handler->name()));
        }
    }
}