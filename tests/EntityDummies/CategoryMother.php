<?php

namespace App\Tests\EntityDummies;


use App\Domain\Product\Category;
use App\Domain\Product\CategoryId;
use App\Domain\Product\CategoryName;

class CategoryMother
{
    public static function createCategory(int $id, string $name): Category
    {
        return new Category(
            new CategoryId($id),
            new CategoryName($name)
        );
    }

    public static function randomCategory(): Category
    {
        return self::createCategory(
            mt_rand(1, 1000),
            substr(str_shuffle("abcdefghijklmnoprstuvwxyz"), 0, 10) 
        );
    }
}
