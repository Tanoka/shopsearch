<?php
declare(strict_types=1);

namespace App\Tests\Application\Product\Integration;

use App\Application\Product\SearcherService;
use App\Application\Product\SearchResponse;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SearcherServiceTest extends KernelTestCase
{
    private ?SearcherService $searchService;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->searchService = $container->get(SearcherService::class);
    }

    public function testSearchByCategory() : void
    {
        $res = $this->searchService->search(null,"boots");

        $this->assertIsArray($res);
        $this->assertCount(3, $res);
        foreach ($res as $product) {
            $this->assertInstanceOf(SearchResponse::class, $product);
        }
    }

    public function testSearchNoResult() : void
    {
        $res = $this->searchService->search(null, "botas");

        $this->assertIsArray($res);
        $this->assertEmpty($res);
    }

    public function testSearchAll() : void
    {
        $res = $this->searchService->search(null, null);

        $this->assertIsArray($res);
        $this->assertCount(5, $res);
        foreach ($res as $product) {
            $this->assertInstanceOf(SearchResponse::class, $product);
        }
    }

    public function testSearchByPriceLessThan() : void
    {
        $res = $this->searchService->search("85000", null);

        $this->assertIsArray($res);
        $this->assertCount(3, $res);
        foreach ($res as $product) {
            $this->assertInstanceOf(SearchResponse::class, $product);
        }
    }
}
