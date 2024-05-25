<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Provider;

class MongoDBOptions
{
    private readonly string $host;

    private readonly string $username;

    private string $password;

    private readonly string $dbName;

    public function __construct(array $options)
    {
        $this->username = '';

        $this->password = '';

        $this->host = $options['host'];

        if (isset($options['user'])) {
            $this->username = urlencode($options['user']);
        }

        if (isset($options['pass'])) {
            $this->password = urlencode($options['pass']);
        }

        $this->dbName = $options['db'];
    }

    public function host(): string
    {
        return $this->host;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function dbName(): string
    {
        return $this->dbName;
    }

    public function clearPass(): void
    {
        $this->password = '';
    }

    public function connectionString(): string
    {
        if ($this->username != '' && $this->password != '') {
            return 'mongodb://'.$this->username.':'.$this->password.'@'.$this->host;
        }
        
        return 'mongodb://'.$this->host;
    }
}