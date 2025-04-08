<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        try {
            DB::beginTransaction();

            // if (isset($data['image_file'])) {
            //     $data['image'] = $this->uploadImage($data['image_file'], 'products');
            //     unset($data['image_file']);
            // }

            $product = $this->productRepository->create($data);
            $product->categories()->sync($data['category_ids'] ?? []);

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw $e;
        }
    }

    public function update(int $id, array $data)
    {
        try {
            DB::beginTransaction();

            $product = $this->productRepository->find($id);

            // if (isset($data['image_file'])) {
            //     if ($product->image && \Storage::disk('public')->exists($product->image)) {
            //         \Storage::disk('public')->delete($product->image);
            //     }

            //     $data['image'] = $this->uploadImage($data['image_file'], 'products');
            //     unset($data['image_file']);
            // }

            $product = $this->productRepository->update($id, $data);
            $product->categories()->sync($data['category_ids'] ?? []);

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw $e;
        }
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
