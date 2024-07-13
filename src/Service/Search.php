<?php
namespace App\Service;

use App\Entity\Product;

class Search 
{
    private const MAX_PRODUCTS = 5;
    
    public function __construct(
        private SearchProductRepositoryInterface $productRepository,
        private DiscountCalculator $discountCalculator
    ) {
        
    }

    public function getAll() : array {
        $res = $this->productRepository->getProducts(self::MAX_PRODUCTS);
        return $this->buildReponse(...$res);
    }

    public function byCategory(string $category): array {
        $res = $this->productRepository->getProductsByCategory(strtolower($category), self::MAX_PRODUCTS);
        return $this->buildReponse(...$res);
    }

    public function byPriceLessThan(string $priceLessThan): array {
        $price = floatval($priceLessThan);
        if (!$price) {
            return [];
        }
        $searchPrice = round($price * Product::PRICE_MULTIPLIER);
        $res = $this->productRepository->getProductsLessThanPrice($searchPrice, self::MAX_PRODUCTS);

        return $this->buildReponse(...$res);
    }

    private function buildReponse(Product ...$res) : array {
        $response = [];
        foreach ($res as $product) {
            // Get discount price if it's applicable
            $discountReponse = $this->discountCalculator->execute($product);

            // Build response dto objects
            $response[] = new ProductResponse($product, $discountReponse["price"], $discountReponse["percentage"]);
        }
        return $response;
    }
}
