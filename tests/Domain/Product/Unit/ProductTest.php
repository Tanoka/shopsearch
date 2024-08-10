<?php
declare(strict_types=1);

namespace App\Tests\Domain\Product\Unit;

use App\Domain\Product\Category;
use App\Domain\Product\CategoryId;
use App\Domain\Product\CategoryName;
use App\Domain\Product\Price;
use App\Domain\Product\Product;
use App\Domain\Product\ProductId;
use App\Domain\Product\ProductName;
use App\Domain\Product\ProductSku;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{

    public function testCreateProduct(): void
    {
        $c =new Category(new CategoryId(100), new CategoryName("boots & shoes"));
        $p = new Product(
            new ProductId(1),
            new ProductSku("00002"),
            new ProductName('test product name'),
            new Price(400000),
            $c
        );

        $this->assertEquals("00002", $p->getSku()->value());
        $this->assertEquals(1, $p->getId()->value());
        $this->assertEquals("test product name", $p->getName()->value());
        $this->assertEquals($c, $p->getCategory());
    }
}
