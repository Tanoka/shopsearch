<?php
declare(strict_types=1);

namespace App\Domain\Product;

use App\Domain\Product\Exception\EmptyProductNameException;
use App\Domain\Product\Exception\ProductNameTooLongException;

class ProductName
{
    private const MAX_LENGTH = 100;

    private string $name;
    public function __construct(string $name)
    {
        $name = trim($name);
        if (!$name) {
            throw new EmptyProductNameException();
        }
        if (strlen($name) < ProductName::MAX_LENGTH) {
            $this->name = $name;
        } else {
            throw new ProductNameTooLongException(ProductName::MAX_LENGTH);
        }
    }

    public function value(): string
    {
        return $this->name;
    }
}
