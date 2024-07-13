<?php

namespace App\Tests\Service\Unit;

use App\Service\ProductResponse;
use App\Tests\EntityDummies\CategoryMother;
use App\Tests\EntityDummies\ProductMother;
use PHPUnit\Framework\TestCase;

class ProductResponseTest extends TestCase
{
    public function testBuilderProductReponse()
    {
        $category = CategoryMother::createCategory(1, "boots");
        $product = ProductMother::createProduct("00003", "BV Lean leather ankle boots", "89000", $category);
        $prodRes = new ProductResponse($product, 89000, null);
        $response = json_encode($prodRes);
        $this->assertEquals(
            '{"sku":"00003","name":"BV Lean leather ankle boots","category":"boots","price":{"original":89000,"final":89000,"discount_percentage":null,"currency":"EUR"}}',
            $response
        );
    }

    public function testBuilderProductReponseWithDicount()
    {
        $category = CategoryMother::createCategory(1, "boots");
        $product = ProductMother::createProduct("00003", "BV Lean leather ankle boots", "89000", $category);
        $prodRes = new ProductResponse($product, 67000, 2000);
        $response = json_encode($prodRes);
        $this->assertEquals(
            '{"sku":"00003","name":"BV Lean leather ankle boots","category":"boots","price":{"original":89000,"final":67000,"discount_percentage":"20%","currency":"EUR"}}',
            $response
        );
    }

}