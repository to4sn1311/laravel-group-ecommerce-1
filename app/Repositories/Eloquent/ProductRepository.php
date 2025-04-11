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

    public function search(array $filters, $perPage = 5)
    {
        return $this->model->with('categories')->filter($filters)->orderByDesc('id')->paginate($perPage);
    }
}
