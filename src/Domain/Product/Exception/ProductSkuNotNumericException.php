<?php

namespace App\Domain\Product\Exception;

class ProductSkuNotNumericException extends ProductException
{
    public function __construct($code = 0, \Exception $previous = null)
    {
        $message = "Product sku must be a integer number";
        parent::__construct($message, $code, $previous);
    }
}
