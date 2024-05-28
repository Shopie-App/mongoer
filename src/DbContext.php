<?php

declare(strict_types=1);

namespace Shopie\Mongoer;

use MongoDB\Database;
use Shopie\Mongoer\Provider\MongoDB;
use Shopie\Mongoer\Provider\MongoDBOptions;
use Shopie\Mongoer\RepositoryBuilder\RepositoryBuilder;

abstract class DbContext
{
    private MongoDB $db;

    public function configure(MongoDBOptions $options)
    {
        $this->db = new MongoDB($options);
    }

    public function db(): Database
    {
        return $this->db->db();
    }

    public function connect(): bool
    {
        return $this->db->connect();
    }

    public function configureRepositories(): void
    {
        (new RepositoryBuilder())->build($this);
    }
}