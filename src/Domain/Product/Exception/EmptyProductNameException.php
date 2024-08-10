<?php

namespace App\Domain\Product\Exception;

class EmptyProductNameException extends ProductException
{
    public function __construct($code = 0, \Exception $previous = null)
    {
        $message = "Product name can't be empty";
        parent::__construct($message, $code, $previous);
    }
}
