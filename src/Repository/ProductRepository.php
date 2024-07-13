<?php

namespace App\Repository;

use App\Entity\Product;
use App\Service\SearchProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository implements SearchProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getProductsByCategory(string $category, int $limit): array 
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c')
            ->andWhere('c.name = :val')
            ->setParameter('val', $category)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getProductsLessThanPrice(int $price, int $limit): array 
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.price < :val')
            ->setParameter('val', $price)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getProducts(int $limit): array 
    {
        return $this->createQueryBuilder('p')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
