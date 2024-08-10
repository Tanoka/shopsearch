<?php

namespace App\Domain\Product;

interface SearchProductRepositoryInterface 
{
    public function getProductsByCategory(CategoryName $category, int $limit): array;

    public function getProductsLessThanPrice(Price $price, int $limit): array;

    public function getProducts(int $limit): array;
}
