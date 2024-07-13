<?php

namespace App\Tests\Service\Unit;

use App\Controller\ProductsController;
use App\Service\Search;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductsControllerTest extends TestCase
{
    private ProductsController | MockObject $controller;
    private Search | MockObject $searchService;

    protected function setUp() : void {
        $this->controller = new ProductsController();

        $container = $this->createMock(ContainerInterface::class);
        $this->controller->setContainer($container);
        $this->searchService = $this->createMock(Search::class);
    }

    public function testNoParameters(): void
    {
        $this->searchService->expects(self::once())->method("getAll")->willReturn([1]);
        $this->searchService->expects(self::never())->method("byPriceLessThan");
        $this->searchService->expects(self::never())->method("byCategory");

        $request = new Request();
        $res = $this->controller->search($request, $this->searchService);
        $this->assertEquals('[1]', $res->getContent());
    }

    public function testCategoryParameter(): void
    {
        $this->searchService->expects(self::never())->method("getAll");
        $this->searchService->expects(self::never())->method("byPriceLessThan");
        $this->searchService->expects(self::once())->method("byCategory")->willReturn([1]);

        $request = new Request(['category' => 'boots']);
        $res = $this->controller->search($request, $this->searchService);
        $this->assertEquals('[1]', $res->getContent());
    }

    public function testPriceParameter(): void
    {
        $this->searchService->expects(self::never())->method("getAll");
        $this->searchService->expects(self::once())->method("byPriceLessThan")->willReturn([1]);
        $this->searchService->expects(self::never())->method("byCategory");

        $request = new Request(['priceLessThan' => "80"]);
        $res = $this->controller->search($request, $this->searchService);
        $this->assertEquals('[1]', $res->getContent());
    }


}
