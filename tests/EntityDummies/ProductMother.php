<?php
namespace App\Tests\EntityDummies;

use App\Entity\Category;
use App\Entity\Product;

class ProductMother
{
    public static function createProduct(string $sku, string $name, int $price, Category $category) : Product {
        $prod = new Product();
        $prod->setSku($sku)
            ->setName($name)
            ->setPrice($price)
            ->setCategory($category);

        return $prod;
    }

    public static function randomProduct(?Category $category = null) : Product 
    {
        $caracters = "abcdefghijklmnoprstuvwxyz";
        $sku = substr(str_shuffle("0123456789"), 0, 8);
        $name = substr(str_shuffle($caracters), 0, 5) . " " . substr(str_shuffle($caracters), 0, 5);
        $price = random_int(10000, 999000);
        if (!$category) {
            $category = CategoryMother::randomCategory();
        }
        return self::createProduct($sku, $name, $price, $category);
    }
}