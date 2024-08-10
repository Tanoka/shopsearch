<?php

namespace App\Domain\Discount;

use App\Domain\Product\Product;

interface DiscountRepositoryInterface
{
    public function getDiscountsToProduct(Product $product): array;
}
