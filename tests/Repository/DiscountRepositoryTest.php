<?php

namespace App\tests\Repository;

use App\Entity\Discount;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DiscountRepositoryTest extends KernelTestCase 
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

    public function testGetDiscountsToProductEmpty() : void 
    {
        $product = $this->entityManager->getRepository((Product::class))->findOneBy(['sku' => '000005']);
        $res = $this->entityManager
            ->getRepository(Discount::class)
            ->getDiscountsToProduct($product);

        $this->assertIsArray($res);
        $this->assertEmpty($res);
    }

    public function testGetDiscountsOneDiscount() : void 
    {
        //Only category discount
        $product = $this->entityManager->getRepository((Product::class))->findOneBy(['sku' => '000001']);

        $res = $this->entityManager
            ->getRepository(Discount::class)
            ->getDiscountsToProduct($product);

        $this->assertIsArray($res);
        $this->assertCount(1, $res);
        $this->assertInstanceOf(Discount::class, $res[0]);
        $this->assertEquals('1500', $res[0]->getPercentage());
    }

    public function testGetDiscountsTwoDiscount() : void 
    {
        //Category and sku discount
        $product = $this->entityManager->getRepository((Product::class))->findOneBy(['sku' => '000002']);

        $res = $this->entityManager
            ->getRepository(Discount::class)
            ->getDiscountsToProduct($product);

        $this->assertIsArray($res);
        $this->assertCount(2, $res);
        $this->assertInstanceOf(Discount::class, $res[0]);
        $this->assertEquals('3000', $res[0]->getPercentage());
    }

}