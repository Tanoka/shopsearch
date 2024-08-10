<?php
declare(strict_types=1);

namespace App\Tests\Domain\Product\Unit;

use App\Domain\Product\Exception\EmptyProductNameException;
use App\Domain\Product\Exception\ProductNameTooLongException;
use App\Domain\Product\ProductName;
use PHPUnit\Framework\TestCase;

class ProductNameTest extends TestCase
{
    public function testEmptyName(): void
    {
        $this->expectException(EmptyProductNameException::class);
        new ProductName("   \n ");
    }

    public function testTooLongName(): void
    {
        $this->expectException(ProductNameTooLongException::class);
        new ProductName(str_repeat("ab", 100));
    }

    public function testNameOk(): void
    {
        $pn = new ProductName("boots");
        $this->assertEquals("boots", $pn->value());
    }

    public function testNameFixed(): void
    {
        $pn = new ProductName("  boots  \t  ");
        $this->assertEquals("boots", $pn->value());
    }
}
