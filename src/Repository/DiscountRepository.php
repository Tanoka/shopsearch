<?php

namespace App\Repository;

use App\Entity\Discount;
use App\Entity\Product;
use App\Service\SearchDiscountRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Discount>
 */
class DiscountRepository extends ServiceEntityRepository implements SearchDiscountRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discount::class);
    }

    public function getDiscountsToProduct(Product $product): array
    {
        $disSku = $this->findOneBy(['discount_type' => 'sku', 'discount_type_id' => $product->getId()]);
        $disCategory = $this->findOneBy(['discount_type' => 'category', 'discount_type_id' => $product->getCategory()->getId()]);

        $res = array_merge([], $disSku ? [$disSku] : [], $disCategory ? [$disCategory] : []);

        return $res;
    }
}
