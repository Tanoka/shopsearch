<?php

namespace App\Service;

use App\Entity\Product;

class DiscountCalculator
{
    public function __construct(private SearchDiscountRepositoryInterface $discountRepo)
    {
        
    }

    public function execute(Product $product): array
    {
        $discounts = $this->discountRepo->getDiscountsToProduct($product);

        $percentageToApply = null;
        foreach ($discounts as $discount) {
            if (!$percentageToApply || $percentageToApply < $discount->getPercentage()) {
                $percentageToApply = $discount->getPercentage();
            }
        }

        if ($percentageToApply) {
            $finalPrice = $this->applyDiscount($product->getPrice(), $percentageToApply);
        } else {
            $finalPrice = $product->getPrice();
        }

        return ["price" => $finalPrice, "percentage" => $percentageToApply];
    }

    private function applyDiscount(int $price, int $percentage): int 
    {
        return $price - (($percentage / 10000) * $price);
    }
}
