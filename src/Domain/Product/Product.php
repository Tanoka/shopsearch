<?php
declare(strict_types=1);

namespace App\Domain\Product;

class Product
{
    private ProductId $id;

    private ProductSku $sku;

    private ProductName $name;

    private Price $price;

    private Category $category;

    public function __construct(ProductId $id, ProductSku $sku, ProductName $name, Price $price, Category $category)
    {
        $this->id = $id;
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getSku(): ProductSku
    {
        return $this->sku;
    }

    public function getName(): ProductName
    {
        return $this->name;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }
}
