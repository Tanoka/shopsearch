<?php

namespace App\Domain\Discount;

enum DiscountType: string
{
    case Product = "product";
    case Category = "category";
}
