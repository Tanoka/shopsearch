<?php
declare(strict_types=1);

namespace App\Domain\Product;

use App\Domain\Discount\Percentage;
use App\Domain\Product\Exception\NegativePriceException;

class Price
{
    private const CENTS_MULTIPLIER = 1000;

    private int $price;

    public function __construct(float $amount, bool $cents = true)
    {
        if ($amount < 0) {
            throw new NegativePriceException();
        }
        if (!$cents) {
            $amount = intval($amount * self::CENTS_MULTIPLIER);
        }
        $this->price = intval($amount);
    }

    public function value(): int
    {
        return $this->price;
    }

    public function calculateDiscountPercentage(Percentage $percentage): Price
    {
        return new Price(
            $this->value()
            - intval(
                (($percentage->value() / Percentage::DISCOUNT_MULTIPLIER) * $this->value())
                / Percentage::DISCOUNT_MULTIPLIER
            )
        );
    }
}
