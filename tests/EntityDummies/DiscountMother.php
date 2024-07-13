<?php

namespace App\Tests\EntityDummies;

use App\Entity\Discount;

class DiscountMother
{
    public static function createDiscount(string $type, int $typeId, int $percentage): Discount {
        $dis = new Discount();
        $dis->setId(mt_rand(1,100))
            ->setDiscountType($type)
            ->setDiscountTypeId($typeId)
            ->setPercentage($percentage);
        return $dis;
    }

    public static function createDiscountCategory(int $categoryId, int $percentage) : Discount {
        return self::createDiscount('category', $categoryId, $percentage);
    }   

    public static function createDiscountSku(int $skuId, int $percentage) : Discount {
        return self::createDiscount('sku', $skuId, $percentage);
    }   
}
