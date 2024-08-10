<?php
declare(strict_types=1);

namespace App\Domain\Product;

class Category
{
    private CategoryId $id;

    private CategoryName $name;

    public function __construct(CategoryId $id, CategoryName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): CategoryId
    {
        return $this->id;
    }

    public function getName(): CategoryName
    {
        return $this->name;
    }
}
