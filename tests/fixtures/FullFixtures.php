<?php

namespace App\Fixtures;

use App\Entity\Category;
use App\Entity\Discount;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class FullFixtures extends Fixture implements FixtureGroupInterface
{
     public static function getGroups(): array
     {
         return ['full'];
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

        $discount = (new Discount())
        ->setDiscountType('category')
        ->setDiscountTypeId($category1->getId())
        ->setPercentage(1500);
        $manager->persist($discount);
        $manager->flush();

        $caracters = "abcdefghijklmnoprstuvwxyz";
        $descuentos = [1000, 1500, 2000, 3000];

        for ($x = 0; $x < 1_000; $x++) {
            $product = (new Product())
                ->setSku(str_pad($x, 6, '0', STR_PAD_LEFT))
                ->setName(substr(str_shuffle($caracters), 0, 7) . " " . substr(str_shuffle($caracters), 0, 10))
                ->setPrice(mt_rand(10, 99) . '000');
            $cat = "category" . mt_rand(1, 3);
            $product->setCategory($$cat);
            $manager->persist($product);
            $manager->flush();
            if (mt_rand(1, 5) == 1) {
                $discount = (new Discount())
                    ->setDiscountType('sku')
                    ->setDiscountTypeId($product->getId())
                    ->setPercentage($descuentos[array_rand($descuentos)]);
                $manager->persist($discount);
                $manager->flush();
            }
        }
    }
}
