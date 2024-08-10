<?php

namespace App\Domain\Product\Exception;

class ProductSkuTooLongException extends ProductException
{
    public function __construct($maxLength, $code = 0, \Exception $previous = null)
    {
        $message = "Product sku length must be sorter than {$maxLength}";
        parent::__construct($message, $code, $previous);
    }
}
