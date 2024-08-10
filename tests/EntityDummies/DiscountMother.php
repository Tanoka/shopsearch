<?php

namespace App\Tests\EntityDummies;

use App\Domain\Discount\Discount;
use App\Domain\Discount\DiscountId;
use App\Domain\Discount\DiscountType;
use App\Domain\Discount\Percentage;
use App\Domain\Product\CategoryId;
use App\Domain\Product\ProductId;
use App\Domain\Shared\EntityId;

class DiscountMother
{
    private static function createDiscount(DiscountType $type, EntityId $typeId, Percentage $percentage): Discount {
        return new Discount(
            new DiscountId(mt_rand(1, 100)),
            $type,
            $typeId,
            $percentage

        );
    }

    public static function createDiscountCategory(int $categoryId, int $percentage) : Discount {
        return self::createDiscount(
            DiscountType::Category,
            new CategoryId($categoryId),
            new Percentage($percentage)
        );
    }

    public static function createDiscountSku(int $productId, int $percentage) : Discount {
        return self::createDiscount(
            DiscountType::Product,
            new ProductId($productId),
            new Percentage($percentage)
        );
    }
}
