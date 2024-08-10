<?php
namespace App\Tests\EntityDummies;

use App\Domain\Product\Category;
use App\Domain\Product\Price;
use App\Domain\Product\Product;
use App\Domain\Product\ProductId;
use App\Domain\Product\ProductName;
use App\Domain\Product\ProductSku;

class ProductMother
{
    public static function createProduct(string $sku, string $name, int $price, Category $category) : Product {
        return new Product(
            new ProductId(mt_rand(1, 100)),
            new ProductSku($sku),
            new ProductName($name),
            new Price($price),
            $category
        );
    }

    public static function randomProduct(?int $price = null, ?Category $category = null) : Product
    {
        $caracters = "abcdefghijklmnoprstuvwxyz";
        $sku = substr(str_shuffle("0123456789"), 0, 8);
        $name = substr(str_shuffle($caracters), 0, 5) . " " . substr(str_shuffle($caracters), 0, 5);
        if (is_null($price)) {
            $price = random_int(10000, 999000);
        }
        if (!$category) {
            $category = CategoryMother::randomCategory();
        }
        return self::createProduct($sku, $name, $price, $category);
    }
}