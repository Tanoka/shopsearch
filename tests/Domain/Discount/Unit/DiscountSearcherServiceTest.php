<?php
declare(strict_types=1);

namespace App\Tests\Domain\Discount\Unit;

use App\Domain\Discount\Discount;
use App\Domain\Discount\DiscountSearcherService;
use App\Domain\Discount\DiscountRepositoryInterface;
use App\Tests\EntityDummies\DiscountMother;
use App\Tests\EntityDummies\ProductMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DiscountSearcherServiceTest extends TestCase
{
    private DiscountRepositoryInterface|MockObject $discountRepo;
    private DiscountSearcherService $discountService;

    protected function setUp() : void
    {
        $this->discountRepo = $this->createMock(DiscountRepositoryInterface::class);
        $this->discountService = new DiscountSearcherService($this->discountRepo);
    }

    public function testNoDiscounts(): void
    {
        $this->discountRepo->method('getDiscountsToProduct')->willReturn([]);
        $prod = ProductMother::randomProduct(80_000);

        $disResponse = $this->discountService->search($prod);

        $this->assertNull($disResponse);
    }

    public function testApplyOneDiscount(): void
    {
        $discount = DiscountMother::createDiscountSku(12, 1500);
        $this->discountRepo->method('getDiscountsToProduct')->willReturn([$discount]);
        
        $prod = ProductMother::randomProduct(89_000);

        $disResponse = $this->discountService->search($prod);

        $this->assertInstanceOf(Discount::class, $disResponse);
        $this->assertEquals($discount, $disResponse);
    }

    public function testApplyTwoDiscount(): void
    {
        $discountMinor = DiscountMother::createDiscountSku(12, 1500);
        $discountPlus = DiscountMother::createDiscountSku(12, 3000);

        $this->discountRepo->method('getDiscountsToProduct')->willReturn([$discountPlus, $discountMinor]);

        $prod = ProductMother::randomProduct(89_000);

        $disResponse = $this->discountService->search($prod);

        $this->assertInstanceOf(Discount::class, $disResponse);
        $this->assertEquals($discountPlus, $disResponse);
    }
}
