<?php
namespace App\Controller;

use App\Service\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'products_search', methods:'GET', format: 'json')]
    public function search(Request $request, Search $searchService): JsonResponse
    {
        $priceLessThan = $request->query->get('priceLessThan');
        $category = $request->query->get('category');

        if ($priceLessThan) {
            $productResponse = $searchService->byPriceLessThan($priceLessThan);
        } elseif ($category) {
            $productResponse = $searchService->byCategory($category);
        } else {
            $productResponse = $searchService->getAll();
        }

        return $this->json($productResponse, 200);
    }
}
