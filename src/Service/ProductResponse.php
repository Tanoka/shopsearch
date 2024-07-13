<?php
namespace App\Service;

use App\Entity\Product;

class ProductResponse
{
    public string $sku;
    public string $name;
    public string $category;
    public object $price;

    public function __construct(Product $product, int $finalPrice, ?int $discountPercentage)
    {
        $this->sku = $product->getSku();
        $this->name = $product->getName();
        $this->category = $product->getCategory()->getName();

        $this->price = new class {
            public int $original;
            public int $final;
            public ?string $discount_percentage;
            public string $currency = 'EUR';
        };    
        $this->price->original = $product->getPrice();
        $this->price->final = $finalPrice;
        $this->price->discount_percentage = $discountPercentage ? round($discountPercentage / 100, 2) . "%" : null;
    }
}
