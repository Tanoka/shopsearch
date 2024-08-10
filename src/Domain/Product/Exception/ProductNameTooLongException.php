<?php

namespace App\Domain\Product\Exception;

class ProductNameTooLongException extends ProductException
{
    public function __construct($maxLength, $code = 0, \Exception $previous = null)
    {
        $message = "Product name length must be sorter than {$maxLength}";
        parent::__construct($message, $code, $previous);
    }
}
