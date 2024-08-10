<?php
declare(strict_types=1);

namespace App\Domain\Product;

use App\Domain\Product\Exception\ProductSkuNotNumericException;

class ProductSku
{
    const MAX_LENGTH = 8;
    private string $sku;

    public function __construct(string $sku)
    {
        if (!is_numeric($sku))
        {
            throw new ProductSkuNotNumericException();
        }

        if (strlen($sku) <= ProductSku::MAX_LENGTH) {
            $this->sku = $sku;
        } else {
            throw new ProductSkuNotNumericException(ProductSku::MAX_LENGTH);
        }
    }

    public function value(): string
    {
        return $this->sku;
    }

    public function equals(ProductSku $sku): bool
    {
        return $this->value() === $sku->value();
    }
}
