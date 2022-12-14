<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;

class QueryService 
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {}

    public function findProduct(int $productId): ?Product 
    {
        return $this->productRepository->find($productId);
    }

    public function getAllProducts(): array 
    {
        return $this->productRepository->findAll();
    }
}
