<?php

namespace App\Tests\Integration;

use App\Service\ProductResponse;
use App\Service\Search;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SearchTest extends KernelTestCase
{
    private ?Search $searchService;

    protected function setUp(): void
    {
        $kernel = self::bootKernel(['debug' => false]);
        $container = static::getContainer();

        $this->searchService = $container->get(Search::class);
    }

    public function testSearchByCategory() : void
    {
        $res = $this->searchService->byCategory("boots");

        $this->assertIsArray($res);
        $this->assertCount(3, $res);
        foreach ($res as $product) {
            $this->assertInstanceOf(ProductResponse::class, $product);
        }
    }

    public function testSearchNoResult() : void
    {
        $res = $this->searchService->byCategory("botas");

        $this->assertIsArray($res);
        $this->assertEmpty($res);
    }

    public function testSearchAll() : void
    {
        $res = $this->searchService->getAll();

        $this->assertIsArray($res);
        $this->assertCount(5, $res);
        foreach ($res as $product) {
            $this->assertInstanceOf(ProductResponse::class, $product);
        }
    }

    public function testSearchByPriceLessThan() : void
    {
        $res = $this->searchService->byPriceLessThan("80.5");

        $this->assertIsArray($res);
        $this->assertCount(3, $res);
        foreach ($res as $product) {
            $this->assertInstanceOf(ProductResponse::class, $product);
        }
    }
}
