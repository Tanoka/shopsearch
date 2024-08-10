<?php

namespace App\Tests\Infrastructure\Repository;

use App\Domain\Product\CategoryName;
use App\Domain\Product\Price;
use App\Domain\Product\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
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

    public function testGetResult(): void
    {
        $product = $this->entityManager
            ->getRepository(\App\Infrastructure\Entity\Product::class)
            ->getProducts(5);

        $this->assertIsArray($product);
        $this->assertCount(5, $product);
        $this->assertInstanceOf(Product::class, $product[0]);
    }

    public function testGetProductsByCategory(): void
    {
        $result = $this->entityManager
            ->getRepository(\App\Infrastructure\Entity\Product::class)
            ->getProductsByCategory(
                new CategoryName('boots'),
                5
            );

        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        foreach ($result as $product) {
            $this->assertInstanceOf(Product::class, $product);
            $this->assertEquals('boots', $product->getCategory()->getName()->value());
        }
    }

    public function testGetProductsByPrice(): void
    {
        $result = $this->entityManager
            ->getRepository(\App\Infrastructure\Entity\Product::class)
            ->getProductsLessThanPrice(new Price(75000), 5);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        foreach ($result as $product) {
            $this->assertInstanceOf(Product::class, $product);
            $this->assertLessThan(75000, $product->getPrice()->value());
        }
    }

    public function testGetProductsNotFound(): void
    {
        $result = $this->entityManager
            ->getRepository(\App\Infrastructure\Entity\Product::class)
            ->getProductsByCategory(new CategoryName('not found'), 5);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
