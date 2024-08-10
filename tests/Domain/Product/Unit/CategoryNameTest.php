<?php

namespace App\Tests\Domain\Product\Unit;

use App\Domain\Product\CategoryName;
use App\Domain\Product\Exception\CategoryNameTooLongException;
use PHPUnit\Framework\TestCase;

class CategoryNameTest extends TestCase
{
    public function testTooLongCategoryName(): void
    {
        $this->expectException(CategoryNameTooLongException::class);
        new CategoryName(str_repeat("boots", 100));
    }

    public function testCreateCategory(): void
    {
        $c = new CategoryName("boots");
        $this->assertEquals("boots", $c->value());
    }
}
