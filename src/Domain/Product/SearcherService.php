<?php
declare(strict_types=1);

namespace App\Domain\Product;

interface SearcherService
{
    const MAX_PRODUCTS = 5;
    
    public function search(?string $priceLessThan, ?string $category): array;
}
