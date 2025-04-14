<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\UploadedFile;
use App\Traits\HandleImage;
use Illuminate\Support\Facades\Request;

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

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->uploadImage($data['image']);
        }

        $product = $this->productRepository->create($data);
        $product->categories()->sync($categories);

        return $product;
    }

    public function update(int $id, array $data)
    {
        $categories = $data['categories'] ?? [];
        unset($data['categories']);

        $product = $this->productRepository->find($id);

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            if ($product->image) {
                $this->deleteImage($product->image);
            }
            $data['image'] = $this->uploadImage($data['image']);
        }

        $product = $this->productRepository->update($id, $data);
        $product->categories()->sync($categories);

        return $product;
    }

    public function delete(int $id)
    {
        $product = $this->productRepository->find($id);
        if ($product->image) {
            $this->deleteImage($product->image);
        }
        return $this->productRepository->delete($id);
    }


    public function getAllWithCategories()
    {
        return $this->productRepository->getAllWithCategories();
    }

    public function search(array $filters, int $perPage = 5)
    {
        return $this->productRepository->search($filters, $perPage);
    }
}
