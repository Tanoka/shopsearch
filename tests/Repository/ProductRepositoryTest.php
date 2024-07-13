<?php

namespace App\tests\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase 
{
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel(['debug' => false]);

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

    public function testGetResult() : void {
        $product = $this->entityManager
            ->getRepository(Product::class)
            ->getProducts(5);

        $this->assertIsArray($product);
        $this->assertCount(5, $product);
        $this->assertInstanceOf(Product::class, $product[0]);
    }

    public function testGetProductsByCategory() : void {
        $result = $this->entityManager
            ->getRepository(Product::class)
            ->getProductsByCategory('boots', 5);

        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        foreach ($result as $product) {
            $this->assertInstanceOf(Product::class, $product);
            $this->assertEquals('boots', $product->getCategory()->getName());
        }
    }

    public function testGetProductsByPrice() : void {
        $result = $this->entityManager
            ->getRepository(Product::class)
            ->getProductsLessThanPrice(75000, 5);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        foreach ($result as $product) {
            $this->assertInstanceOf(Product::class, $product);
            $this->assertLessThan(75000, $product->getPrice());
        }
    }

    public function testGetProductsNotFound() : void {
        $result = $this->entityManager
            ->getRepository(Product::class)
            ->getProductsByCategory('not found', 5);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}