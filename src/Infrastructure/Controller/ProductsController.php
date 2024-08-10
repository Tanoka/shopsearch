<?php
declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Product\SearcherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'products_search', methods:'GET', format: 'json')]
    public function search(Request $request, SearcherService $searchService): JsonResponse
    {
        $productResponse = $searchService->search(
            $request->query->get('priceLessThan'),
            $request->query->get('category')
        );

        return $this->json($productResponse, 200);
    }
}
