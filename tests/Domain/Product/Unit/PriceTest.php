<?php
declare(strict_types=1);

namespace App\Tests\Domain\Product\Unit;

use App\Domain\Discount\Percentage;
use App\Domain\Product\Exception\NegativePriceException;
use App\Domain\Product\Price;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{

    public function testNegativePrice(): void
    {
        $this->expectException(NegativePriceException::class);
        new Price(-100);
    }

    public function testCentsPrice(): void
    {
        $p = new Price(5000);
        $this->assertEquals(5000, $p->value());
    }

    public function testEurosPrice(): void
    {
        $p = new Price(50, false);
        $this->assertEquals(50000, $p->value());
    }

    public function testDiscountPrice(): void
    {
        $p = new Price(50000);
        $np = $p->calculateDiscountPercentage(new Percentage(2000));
        $this->assertEquals(40000, $np->value());
    }
}
