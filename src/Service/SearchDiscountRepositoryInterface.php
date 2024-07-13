<?php
namespace App\Service;

use App\Entity\Product;

interface SearchDiscountRepositoryInterface 
{
    public function getDiscountsToProduct(Product $product): array;
}
