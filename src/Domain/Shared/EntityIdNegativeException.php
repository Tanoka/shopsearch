<?php

namespace App\Domain\Shared;

class EntityIdNegativeException extends \Exception
{
    public function __construct($code = 0, \Exception $previous = null)
    {
        $message = "Id can't be negative";
        parent::__construct($message, $code, $previous);
    }
}