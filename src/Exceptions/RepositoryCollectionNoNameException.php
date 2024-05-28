<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Exceptions;

class RepositoryCollectionNoNameException extends \Exception
{
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        parent::__construct('HandlesCollection attribute has no value for name parameter, for '.$message.' repository', $code, $previous);
    }
}