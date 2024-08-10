<?php
declare(strict_types=1);

namespace App\Domain\Discount;

class Percentage
{
    public const DISCOUNT_MULTIPLIER = 100;
    public const MAX_DISCOUNT = 100 * Percentage::DISCOUNT_MULTIPLIER;
    private int $percentage;

    public function __construct(int $amount)
    {
        if ($amount < 0 || $amount > self::MAX_DISCOUNT) {
            throw new \InvalidArgumentException('Price cannot be negative');
        }
        $this->percentage = $amount;
    }

    public function value(): int
    {
        return $this->percentage;
    }

    public function equals(Percentage $percentage): bool
    {
        return $this->value() === $percentage->value();
    }

    public function minor(Percentage $percentage): bool
    {
        return $this->value() < $percentage->value();
    }
}
