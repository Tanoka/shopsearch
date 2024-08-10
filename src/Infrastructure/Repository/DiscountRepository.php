<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Discount\DiscountId;
use App\Domain\Discount\DiscountType;
use App\Domain\Discount\Percentage;
use App\Domain\Discount\DiscountRepositoryInterface;
use App\Domain\Product\CategoryId;
use App\Domain\Product\Product;
use App\Domain\Product\ProductId;
use App\Infrastructure\Entity\Discount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DiscountRepository extends ServiceEntityRepository implements DiscountRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discount::class);
    }

    public function getDiscountsToProduct(Product $product): array
    {
        $disSku = $this->findOneBy(
            ['discount_type' => DiscountType::Product->value, 'discount_type_id' => $product->getId()->value()]
        );
        $disCategory = $this->findOneBy(
            [
                'discount_type' => DiscountType::Category->value,
                'discount_type_id' => $product->getCategory()->getId()->value()
            ]
        );

        $response = [];
        foreach (array_merge([], $disSku ? [$disSku] : [], $disCategory ? [$disCategory] : []) as $discount) {
            $response[] = $this->buildEntity($discount);
        }
        return $response;
    }

    private function buildEntity(Discount $discount): \App\Domain\Discount\Discount
    {
            $id = ($discount->getDiscountType() == DiscountType::Category)
                ? new ProductId($discount->getDiscountTypeId())
                : new CategoryId($discount->getDiscountTypeId());

            $discountEntity = new \App\Domain\Discount\Discount(
                new DiscountId($discount->getId()),
                match ($discount->getDiscountType()) {
                    DiscountType::Product->value => DiscountType::Product,
                    DiscountType::Category->value => DiscountType::Category,
                    default => throw new \Exception('Unexpected match value')
                },
                $id,
                new Percentage($discount->getPercentage())
            );
        return $discountEntity;
    }
}
