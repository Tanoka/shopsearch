<?php

namespace App\Domain\Product\Exception;

class CategoryNameTooLongException extends ProductException
{
    public function __construct($maxLength, $code = 0, \Exception $previous = null)
    {
        $message = "Category name length must be lesser than {$maxLength}";
        parent::__construct($message, $code, $previous);
    }
}
