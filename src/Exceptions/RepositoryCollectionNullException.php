<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Exceptions;

class RepositoryCollectionNullException extends \Exception
{
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        parent::__construct('Repository '.$message.' is missing HandlesCollection attribute', $code, $previous);
    }
}