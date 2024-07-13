<?php

namespace App\Tests\Service\Unit;

use App\Service\DiscountCalculator;
use App\Service\ProductResponse;
use App\Service\Search;
use App\Service\SearchProductRepositoryInterface;
use App\Tests\EntityDummies\ProductMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{

    private SearchProductRepositoryInterface | MockObject $searchRepo;
    private DiscountCalculator | MockObject $discountCalculator;
    private Search $searchService;

    protected function setUp(): void
    {
        $this->searchRepo = $this->createMock(SearchProductRepositoryInterface::class);
        $this->discountCalculator = $this->createMock(DiscountCalculator::class);
        $this->searchService = new Search($this->searchRepo, $this->discountCalculator);
    }

    public function testSearchNoResultByCategory(): void
    {
        $this->searchRepo->method('getProductsByCategory')->willReturn([]);
        $this->discountCalculator->expects(self::never())->method('execute');

        $result = $this->searchService->byCategory("Cars");

        $this->assertEmpty($result);
    }

    public function testSearchNoResultByPrice(): void
    {
        $this->searchRepo->method('getProductsLessThanPrice')->willReturn([]);
        $this->discountCalculator->expects(self::never())->method('execute');

        $result = $this->searchService->byCategory("Cars");

        $this->assertEmpty($result);
    }

    public function testSearchResultObjectsAndDiscountsValuesApplied() : void {
        $product1 = ProductMother::randomProduct();
        $product2 = ProductMother::randomProduct();
        $product3 = ProductMother::randomProduct();
        $getProducts = [$product1, $product2, $product3];
        $this->searchRepo->method('getProducts')->willReturn($getProducts);
        $resDiscount = [
            ["price" => 100, "percentage" => 2000],
            ["price" => $product2->getPrice(), "percentage" => null],
            ["price" => $product3->getPrice(), "percentage" => 3000]
        ];

        $this->discountCalculator->method('execute')->will(
            $this->onConsecutiveCalls($resDiscount[0], $resDiscount[1], $resDiscount[2])
        );

        $result = $this->searchService->getAll();

        $this->assertIsArray($result);
        $this->assertEquals(3, count($result));
        
        for ($x = 0; $x < count($result); $x++) {
            $this->assertInstanceOf(ProductResponse::class, $result[$x]);
            $this->assertEquals($getProducts[$x]->getSku(), $result[$x]->sku);
            $this->assertEquals($getProducts[$x]->getName(), $result[$x]->name);
            $this->assertEquals($getProducts[$x]->getCategory()->getName(), $result[$x]->category);
            $this->assertEquals($getProducts[$x]->getPrice(), $result[$x]->price->original);
            $this->assertEquals($resDiscount[$x]["price"], $result[$x]->price->final);
            if ($x == 0) {
                $this->assertEquals("20%", $result[$x]->price->discount_percentage);
            } elseif ($x == 1) {
                $this->assertNull($result[$x]->price->discount_percentage);
            } elseif ($x == 2) {
                $this->assertEquals("30%", $result[$x]->price->discount_percentage);
            }

            $this->assertEquals("EUR", $result[$x]->price->currency);
        }
    }

    public function testSearchCategoryMatch() : void {
        $product1 = ProductMother::randomProduct();
        $product2 = ProductMother::randomProduct();
        $this->searchRepo->method('getProductsByCategory')->willReturn([$product1, $product2]);
        $this->discountCalculator->method('execute')->will(
            $this->onConsecutiveCalls(
            ["price" => $product1->getPrice(), "percentage" => null],
            ["price" => $product2->getPrice(), "percentage" => null]
            )
        );

        $result = $this->searchService->byCategory("category nice");

        $this->assertIsArray($result);
        $this->assertEquals(2, count($result));
        foreach ($result as $product) {
            $this->assertInstanceOf(ProductResponse::class, $product);
        }
    }

    public function testSearchPriceMatch() : void {
        $product1 = ProductMother::randomProduct();
        $product2 = ProductMother::randomProduct();
        $this->searchRepo->method('getProductsLessThanPrice')->willReturn([$product1, $product2]);
        $this->discountCalculator->method('execute')->will(
            $this->onConsecutiveCalls(
            ["price" => $product1->getPrice(), "percentage" => null],
            ["price" => $product2->getPrice(), "percentage" => null]
            )
        );

        $result = $this->searchService->byPriceLessThan("80000");

        $this->assertIsArray($result);
        $this->assertEquals(2, count($result));
        foreach ($result as $product) {
            $this->assertInstanceOf(ProductResponse::class, $product);
        }
    }

    public function testSearchPriceNotNumber() : void {
        $this->searchRepo->expects(self::never())->method('getProductsLessThanPrice');

        $result = $this->searchService->byPriceLessThan("lol");

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testSearchCategoryCapsLetters() : void {
        $this->searchRepo->method('getProductsByCategory')->with("shoés")->willReturn([]);
        
        $result = $this->searchService->byCategory("ShOéS");

        $this->assertEmpty($result);
    }
}
