<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\ProductsView;

readonly class GetProductsController
{
    public function __construct(
        private ProductsView $productsView,
        private ProductRepository $productRepository
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();

        $rawRequest = json_decode($request->getBody()->getContents(), true);

        try {
            $data = [];

            foreach ($this->productRepository->getByCategory($rawRequest['category']) as $product) {
                $data[] = $this->productsView->toArray($product);
            }

            $response->getBody()->write(
                json_encode(
                    $data,
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(
                [
                    'status' => 'error',
                    'message' => 'Failed to get products',
                ],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            ));

            return $response
                ->withHeader('Content-Type', 'application/json; charset=utf-8')
                ->withStatus(500);
        }

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
}
