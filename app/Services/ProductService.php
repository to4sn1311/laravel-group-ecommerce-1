<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Traits\HandleImage;

class ProductService
{
    use HandleImage;

    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAll()
    {
        return $this->productRepository->getAll();
    }

    public function find($id)
    {
        return $this->productRepository->find($id);
    }
    public function create(array $data)
    {
        $categories = $data['categories'] ?? [];
        unset($data['categories']);

        $product = $this->productRepository->create($data);
        $product->categories()->sync($categories);

        return $product;
    }

    public function update(int $id, array $data)
    {
        $categories = $data['categories'] ?? [];
        unset($data['categories']);

        $product = $this->productRepository->update($id, $data);
        $product->categories()->sync($categories);

        return $product;
    }

    public function delete(int $id)
    {
        return $this->productRepository->delete($id);
    }


    public function getAllWithCategories()
    {
        return $this->productRepository->getAllWithCategories();
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
