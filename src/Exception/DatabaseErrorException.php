<?php
namespace Owr\Exception;

use Doctrine\DBAL\DBALException;

class DatabaseErrorException extends \RuntimeException
{
    /**
     * DatabaseErrorException constructor
     *
     * @param DBALException $previous
     */
    public function __construct(DBALException $previous)
    {
        parent::__construct($previous->getMessage(), $previous->getCode(), $previous);
    }
}
