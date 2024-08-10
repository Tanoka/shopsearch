<?php
declare(strict_types=1);

namespace App\Application\Product;

use App\Domain\Discount\Discount;
use App\Domain\Discount\Percentage;
use App\Domain\Product\Price;
use App\Domain\Product\Product;

class SearchResponse
{
    public string $sku;
    public string $name;
    public string $category;
    public object $price;

    private const PRECISION = 2;

    public function __construct(Product $product, Price $finalPrice, ?Discount $discount)
    {
        $this->sku = $product->getSku()->value();
        $this->name = $product->getName()->value();
        $this->category = $product->getCategory()->getName()->value();

        $this->price = new class {
            public int $original;
            public int $final;
            public ?string $discount_percentage;
            public string $currency = 'EUR';
        };

        $this->price->original = $product->getPrice()->value();
        $this->price->final = $finalPrice->value();
        $this->price->discount_percentage = $discount
            ? round($discount->getPercentage()->value() / Percentage::DISCOUNT_MULTIPLIER, self::PRECISION) . "%"
            : null;
    }
}
