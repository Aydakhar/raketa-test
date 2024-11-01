<?php

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Repository\Entity\Product;
use Raketa\BackendTestTask\Repository\ProductRepository;

readonly class ProductsView
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function toArray(Product $product): array
    {
        return [
            'id' => $product->getId(),
            'uuid' => $product->getUuid(),
            'name' => $product->getName(),
            'category' => $product->getCategory(),
            'description' => $product->getDescription(),
            'thumbnail' => $product->getThumbnail(),
            'price' => $product->getPrice(),
        ];
    }
}
