<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getAllWithCategories(): Collection
    {
        return $this->model->with('categories')->get();
    }

    public function findByName(string $name)
    {
        return $this->model->where('name', $name)->first();
    }

    public function findByCategoryId(int $categoryId)
    {
        return $this->model->where('category_id', $categoryId)->get();
    }

    public function searchByPrice($minPrice, $maxPrice)
    {
        return $this->model->whereBetween('price', [$minPrice, $maxPrice])->get();
    }
}
