<?php
declare(strict_types=1);

namespace App\Domain\Product;

use App\Domain\Product\Exception\CategoryNameTooLongException;

class CategoryName
{
    private const MAX_LENGTH = 100;

    private string $name;

    public function __construct(string $name)
    {
        $name = trim($name);
        if ($name && strlen($name) < CategoryName::MAX_LENGTH) {
            $this->name = strtolower($name);
        } else {
            throw new CategoryNameTooLongException(CategoryName::MAX_LENGTH);
        }
    }

    public function value(): string
    {
        return $this->name;
    }
}
