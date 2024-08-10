<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Product\Category;
use App\Domain\Product\CategoryId;
use App\Domain\Product\CategoryName;
use App\Domain\Product\Price;
use App\Domain\Product\ProductId;
use App\Domain\Product\ProductName;
use App\Domain\Product\SearchProductRepositoryInterface;
use App\Domain\Product\ProductSku;
use App\Infrastructure\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository implements SearchProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getProductsByCategory(CategoryName $category, int $limit): array
    {
        $products = $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c')
            ->andWhere('c.name = :val')
            ->setParameter('val', $category->value())
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $this->buildResponse($products);
    }

    public function getProductsLessThanPrice(Price $price, int $limit): array
    {
        $products = $this->createQueryBuilder('p')
            ->andWhere('p.price < :val')
            ->setParameter('val', $price->value())
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
        return $this->buildResponse($products);
    }

    public function getProducts(int $limit): array
    {
        $products = $this->createQueryBuilder('p')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
        return $this->buildResponse($products);
    }

    private function buildResponse(array $products): array
    {
        $response = [];
        foreach ($products as $product) {
            $response[] = $this->buildEntity($product);
        }
        return $response;
    }

    private function buildEntity(Product $product)
    {
        return new \App\Domain\Product\Product(
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
