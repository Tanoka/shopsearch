<?php
declare(strict_types=1);

namespace App\Domain\Discount;

use App\Domain\Product\Product;

class DiscountSearcherService
{
    public function __construct(readonly private DiscountRepositoryInterface $discountRepo)
    {
    }

    public function search(Product $product): ?Discount
    {
        $discounts = $this->discountRepo->getDiscountsToProduct($product);

        $discountToApply = null;
        /** @var Discount $discount */
        foreach ($discounts as $discount) {
            if (!$discountToApply || !$discount->minor($discountToApply)) {
                $discountToApply = $discount;
            }
        }
        return $discountToApply;
    }
}
