<?php

declare(strict_types=1);

namespace App\Tests\Domain\Discount\Unit;

use App\Domain\Discount\Percentage;
use PHPUnit\Framework\TestCase;

class PercentageTest extends TestCase
{
    public function testNegativePercentage(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Percentage(-100);
    }

    public function testGreaterThan100Percentage(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Percentage(10001);
    }

    public function compareTwoEqualsPercentages(): void
    {
        $this->assertTrue((new Percentage(20000))->equals(new Percentage(20000)));
    }

    public function compareMinorPercentages(): void
    {
        $this->assertTrue((new Percentage(15000))->minor(new Percentage(20000)));
    }
}
