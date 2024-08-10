<?php

namespace App\Domain\Product\Exception;

class NegativePriceException extends ProductException
{
    public function __construct($code = 0, \Exception $previous = null)
    {
        $message = "Product price must be greater than 0";
        parent::__construct($message, $code, $previous);
    }
}
