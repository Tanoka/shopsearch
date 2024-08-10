<?php

namespace App\Tests\Infrastructure\Repository;

use App\Domain\Discount\Discount as DomainDiscount;
use App\Domain\Product\Category;
use App\Domain\Product\CategoryId;
use App\Domain\Product\CategoryName;
use App\Domain\Product\Price;
use App\Domain\Product\Product as DomainProduct;
use App\Domain\Product\ProductId;
use App\Domain\Product\ProductName;
use App\Domain\Product\ProductSku;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Infrastructure\Entity\Product;
use App\Infrastructure\Entity\Discount;

class DiscountRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel(['debug' => true]);

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testGetDiscountsToProductEmpty(): void
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['sku' => '000005']);
        $res = $this->entityManager
            ->getRepository(Discount::class)
            ->getDiscountsToProduct($this->buildEntity($product));

        $this->assertIsArray($res);
        $this->assertEmpty($res);
    }

    public function testGetDiscountsOneDiscount(): void
    {
        //Only category discount
        $product= $this->entityManager->getRepository(Product::class)->findOneBy(['sku' => '000001']);

        $res = $this->entityManager
            ->getRepository(Discount::class)
            ->getDiscountsToProduct($this->buildEntity($product));

        $this->assertIsArray($res);
        $this->assertCount(1, $res);
        $this->assertInstanceOf(DomainDiscount::class, $res[0]);
        $this->assertEquals('1500', $res[0]->getPercentage()->value());
    }

    public function testGetDiscountsTwoDiscount(): void
    {
        //Category and sku discount
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['sku' => '000002']);

        $res = $this->entityManager
            ->getRepository(Discount::class)
            ->getDiscountsToProduct($this->buildEntity($product));

        $this->assertIsArray($res);
        $this->assertCount(2, $res);
        $this->assertInstanceOf(DomainDiscount::class, $res[0]);
        $this->assertEquals('3000', $res[0]->getPercentage()->value());
    }

    private function buildEntity(Product $product): DomainProduct
    {
        return new DomainProduct(
            new ProductId($product->getId()),
            new ProductSku($product->getSku()),
            new ProductName($product->getName()),
            new Price($product->getPrice()),
            new Category(
                new CategoryId($product->getCategory()->getId()),
                new CategoryName($product->getCategory()->getName())
            )
        );
    }
}
