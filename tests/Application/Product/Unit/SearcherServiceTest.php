<?php
declare(strict_types=1);

namespace App\Tests\Application\Product\Unit;

use App\Application\Product\SearcherService;
use App\Application\Product\SearchResponse;
use App\Domain\Discount\DiscountSearcherService;
use App\Domain\Product\SearchProductRepositoryInterface;
use App\Tests\EntityDummies\DiscountMother;
use App\Tests\EntityDummies\ProductMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SearcherServiceTest extends TestCase
{

    private SearchProductRepositoryInterface | MockObject $searchRepo;
    private DiscountSearcherService | MockObject $discountCalculator;
    private SearcherService $searchService;

    protected function setUp(): void
    {
        $this->searchRepo = $this->createMock(SearchProductRepositoryInterface::class);
        $this->discountCalculator = $this->createMock(DiscountSearcherService::class);
        $this->searchService = new SearcherService($this->searchRepo, $this->discountCalculator);
    }

    public function testSearchNoResultByCategory(): void
    {
        $this->searchRepo->method('getProductsByCategory')->willReturn([]);
        $this->discountCalculator->expects(self::never())->method('search');

        $result = $this->searchService->search(null, "Cars");

        $this->assertEmpty($result);
    }

    public function testSearchNoResultByPrice(): void
    {
        $this->searchRepo->method('getProductsLessThanPrice')->willReturn([]);
        $this->discountCalculator->expects(self::never())->method('search');

        $result = $this->searchService->search("100", null);

        $this->assertEmpty($result);
    }

    public function testSearchResultObjectsAndDiscountsValuesApplied() : void {
        $product1 = ProductMother::randomProduct();
        $product2 = ProductMother::randomProduct();
        $product3 = ProductMother::randomProduct();
        $getProducts = [$product1, $product2, $product3];
        $this->searchRepo->method('getProducts')->willReturn($getProducts);
        $resDiscount = [
            DiscountMother::createDiscountSku($product1->getId()->value(), 20_00),
            null,
            DiscountMother::createDiscountCategory($product2->getCategory()->getId()->value(), 30_00),
        ];

        $this->discountCalculator->method('search')->will(
            $this->onConsecutiveCalls($resDiscount[0], $resDiscount[1], $resDiscount[2])
        );

        $result = $this->searchService->search(null, null);

        $this->assertIsArray($result);
        $this->assertEquals(3, count($result));
        
        for ($x = 0; $x < count($result); $x++) {
            $this->assertInstanceOf(SearchResponse::class, $result[$x]);
            $this->assertEquals($getProducts[$x]->getSku()->value(), $result[$x]->sku);
            $this->assertEquals($getProducts[$x]->getName()->value(), $result[$x]->name);
            $this->assertEquals($getProducts[$x]->getCategory()->getName()->value(), $result[$x]->category);
            $this->assertEquals($getProducts[$x]->getPrice()->value(), $result[$x]->price->original);
            $this->assertObjectHasProperty('final', $result[$x]->price);
            if ($x == 0) {
                $this->assertTrue($getProducts[$x]->getPrice()->value() > $result[$x]->price->final);
                $this->assertEquals("20%", $result[$x]->price->discount_percentage);
            } elseif ($x == 1) {
                $this->assertEquals($getProducts[$x]->getPrice()->value(), $result[$x]->price->final);
                $this->assertNull($result[$x]->price->discount_percentage);
            } elseif ($x == 2) {
                $this->assertTrue($getProducts[$x]->getPrice()->value() > $result[$x]->price->final);
                $this->assertEquals("30%", $result[$x]->price->discount_percentage);
            }

            $this->assertEquals("EUR", $result[$x]->price->currency);
        }
    }

    public function testSearchCategoryMatch() : void {
        $product1 = ProductMother::randomProduct();
        $product2 = ProductMother::randomProduct();
        $this->searchRepo->method('getProductsByCategory')->willReturn([$product1, $product2]);
        $this->discountCalculator->method('search')->willReturn(null);

        $result = $this->searchService->search(null, "category nice");

        $this->assertIsArray($result);
        $this->assertEquals(2, count($result));
        foreach ($result as $product) {
            $this->assertInstanceOf(SearchResponse::class, $product);
        }
    }

    public function testSearchPriceMatch() : void {
        $product1 = ProductMother::randomProduct();
        $product2 = ProductMother::randomProduct();
        $this->searchRepo->method('getProductsLessThanPrice')->willReturn([$product1, $product2]);
        $this->discountCalculator->method('search')->willReturn(null);

        $result = $this->searchService->search("80000", null);

        $this->assertIsArray($result);
        $this->assertEquals(2, count($result));
        foreach ($result as $product) {
            $this->assertInstanceOf(SearchResponse::class, $product);
        }
    }
}
