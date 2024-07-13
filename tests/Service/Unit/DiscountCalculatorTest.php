<?php

namespace App\Tests\Service\Unit;

use App\Service\DiscountCalculator;
use App\Service\SearchDiscountRepositoryInterface;
use App\Tests\EntityDummies\DiscountMother;
use App\Tests\EntityDummies\ProductMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DiscountCalculatorTest extends TestCase
{
    private SearchDiscountRepositoryInterface|MockObject $discountRepo;
    private DiscountCalculator $discountService;

    protected function setUp() : void {
        $this->discountRepo = $this->createMock(SearchDiscountRepositoryInterface::class);       
        $this->discountService = new DiscountCalculator($this->discountRepo);
    }

    public function testNoDiscounts() 
    {
        $this->discountRepo->method('getDiscountsToProduct')->willReturn([]);
        
        $prod = ProductMother::randomProduct();
        $prod->setPrice(80_000);

        $disResponse = $this->discountService->execute($prod);

        $this->assertEquals(80_000, $disResponse["price"]);
        $this->assertNull($disResponse["percentage"]);
    }

    public function testApplyOneDiscount() 
    {

        $this->discountRepo->method('getDiscountsToProduct')->willReturn([DiscountMother::createDiscountSku(12, 1500)]);
        
        $prod = ProductMother::randomProduct();
        $prod->setPrice(89_000);

        $disResponse = $this->discountService->execute($prod);

        $this->assertEquals(75_650, $disResponse["price"]);
        $this->assertEquals(1500, $disResponse["percentage"]);
    }

    public function testApplyTwoDiscount() 
    {
        $this->discountRepo->method('getDiscountsToProduct')->willReturn([
            DiscountMother::createDiscountSku(12, 3000),
            DiscountMother::createDiscountSku(12, 1500)
        ]);
        
        $prod = ProductMother::randomProduct();
        $prod->setPrice(89_000);

        $disResponse = $this->discountService->execute($prod);

        $this->assertEquals(62_300, $disResponse["price"]);
        $this->assertEquals(3000, $disResponse["percentage"]);
    }
}
