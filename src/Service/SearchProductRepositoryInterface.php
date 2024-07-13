<?php

namespace App\Service;

use Doctrine\Common\Collections\ArrayCollection;

interface SearchProductRepositoryInterface 
{
    public function getProductsByCategory(string $category, int $limit): array;

    public function getProductsLessThanPrice(int $price, int $limit): array;

    public function getProducts(int $limit): array;
}