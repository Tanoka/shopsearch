<?php

namespace App\Fixtures;

use App\Domain\Discount\DiscountType;
use App\Infrastructure\Entity\Category;
use App\Infrastructure\Entity\Discount;
use App\Infrastructure\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
     public static function getGroups(): array
     {
         return ['basic'];
     }

    public function load(ObjectManager $manager): void
    {
        $category1 = new Category();
        $category1->setName("boots");
        $manager->persist($category1);
        $category2 = new Category();
        $category2->setName("sandals");
        $manager->persist($category2);
        $category3 = new Category();
        $category3->setName("sneakers");
        $manager->persist($category3);
        $manager->flush();

        $products = [];
        $products[] = (new Product())
            ->setSku("000001")
            ->setName("BV Lean leather ankle boots")
            ->setCategory($category1)
            ->setPrice(89000);
        $products[] = (new Product())
            ->setSku("000002")
            ->setName("BV Lean leather ankle boots")
            ->setCategory($category1)
            ->setPrice(99000);
        $products[] = (new Product())
            ->setSku("000003")
            ->setName("Ashlington leather ankle boots")
            ->setCategory($category1)
            ->setPrice(71000);
        $products[] = (new Product())
            ->setSku("000004")
            ->setName("Naima embellished suede sandals")
            ->setCategory($category2)
            ->setPrice(79500);
        $products[] = (new Product())
            ->setSku("000005")
            ->setName("Nathane leather sneakers")
            ->setCategory($category3)
            ->setPrice(59000);
        foreach ($products as $product) {
            $manager->persist($product);
        }
        $manager->flush();

        // Discount in sku 000002
        $discount1 = (new Discount())
            ->setDiscountType(DiscountType::Product->value)
            ->setDiscountTypeId($products[1]->getId())
            ->setPercentage(3000);
        $manager->persist($discount1);
         
        //Discount in boots category    
        $discount2 = (new Discount())
            ->setDiscountType(DiscountType::Category->value)
            ->setDiscountTypeId($category1->getId())
            ->setPercentage(1500);

        $manager->persist($discount2);

        $manager->flush();
    }
}
