<?php

namespace App\Application\Product;

use App\Domain\Discount\DiscountSearcherService;
use App\Domain\Product\CategoryName;
use App\Domain\Product\Price;
use App\Domain\Product\Product;
use App\Domain\Product\SearcherService as DomainSearchService;
use App\Domain\Product\SearchProductRepositoryInterface;

/*
 * We use an application service instead of a domain service because two aggregates are needed to build the response.
 */
class SearcherService implements DomainSearchService
{
    public function __construct(
        readonly private SearchProductRepositoryInterface $productRepository,
        readonly private DiscountSearcherService $discountCalculatorService
    ) {
    }

    public function search(?string $priceLessThan, ?string $category): array
    {
        //Search products by parameters conditions. Category precedence over price
        if ($category) {
            $res = $this->productRepository->getProductsByCategory(
                new CategoryName($category),
                DomainSearchService::MAX_PRODUCTS
            );
        } elseif ($priceLessThan) {
            $res = $this->productRepository->getProductsLessThanPrice(
            // priceLessThan is not in cents
                new Price(floatval($priceLessThan), true),
                DomainSearchService::MAX_PRODUCTS
            );
        } else {
            $res = $this->productRepository->getProducts(DomainSearchService::MAX_PRODUCTS);
        }

        // Apply discount and build response
        $response = [];
        /** @var Product $product */
        foreach ($res as $product) {
            // Search for possible discounts
            $discount = $this->discountCalculatorService->search($product);
            // TODO demeter law
            // get price with discount if discount is found.
            $discountPrice = $discount
                ?  $product->getPrice()->calculateDiscountPercentage($discount->getPercentage())
                :  $product->getPrice();

            // Build response object
            $response[] = new SearchResponse($product, $discountPrice, $discount);
        }

        return $response;
    }
}
