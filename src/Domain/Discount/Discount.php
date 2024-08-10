<?php
declare(strict_types=1);

namespace App\Domain\Discount;

use App\Domain\Shared\EntityId;

class Discount
{
    private DiscountId $id;

    private DiscountType $type;

    private EntityId $type_id;

    private Percentage $percentage;

    public function __construct(DiscountId $id, DiscountType $type, EntityId $type_id, Percentage $percentage)
    {
        $this->id = $id;
        $this->type = $type;
        $this->type_id = $type_id;
        $this->percentage = $percentage;
    }

    public function getId(): DiscountId
    {
        return $this->id;
    }

    public function getType(): DiscountType
    {
        return $this->type;
    }

    public function getTypeId(): EntityId
    {
        return $this->type_id;
    }

    public function getPercentage(): Percentage
    {
        return $this->percentage;
    }

    public function minor(Discount $discount): bool
    {
        return $this->getPercentage()->minor($discount->getPercentage());
    }
}
