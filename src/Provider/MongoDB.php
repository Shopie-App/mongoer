<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Provider;

use MongoDB\Client;
use MongoDB\Database;

final class MongoDB
{
    private ?Client $dbConnection = null;

    private Database $db;

    public function __construct(private MongoDBOptions $options)
    {
        $this->options = $options;
    }

    public function db(): Database
    {
        return $this->db;
    }

    public function connect(): bool
    {
        try {

            if ($this->dbConnection === null) {
                $this->dbConnection = new Client($this->options->connectionString());
            }

        } catch(\Exception $ex) {

            return false;
        }

        $this->options->clearPass();

        $this->db = $this->dbConnection->selectDatabase($this->options->dbName());
        
        return true;
    }
}