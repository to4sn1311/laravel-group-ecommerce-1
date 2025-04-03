<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductService
{
    protected $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function getAll()
    {
        return $this->productRepository->all();
    }

    public function getById($id)
    {
        return $this->productRepository->find($id);
    }

    public function createProduct(array $data)
    {
        return $this->productRepository->create($data);
    }

    public function updateProduct($id, array $data)
    {
        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct($id)
    {
        return $this->productRepository->delete($id);
    }

    public function searchByCategoryId($categoryId)
    {
        return $this->productRepository->findByCategoryId($categoryId);
    }

    public function searchByPrice($minPrice, $maxPrice)
    {
        return $this->productRepository->searchByPrice($minPrice, $maxPrice);
    }
}
