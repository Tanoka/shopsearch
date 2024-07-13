<?php

namespace App\Tests\EntityDummies;

use App\Entity\Category;

class CategoryMother 
{
    public static function createCategory(int $id, string $name): Category
    {
        $cat = new Category();
        $cat->setId($id)
        ->setName($name);
        return $cat;
    }

    public static function randomCategory(): Category
    {
        return self::createCategory(
            mt_rand(1, 1000),
            substr(str_shuffle("abcdefghijklmnoprstuvwxyz"), 0, 10) 
        );
    }
}
