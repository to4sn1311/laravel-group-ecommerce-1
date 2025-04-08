<?php

namespace App\Repositories\Interfaces;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllWithCategories(): Collection;
    public function findByName(string $name);
    public function findByCategoryId(int $categoryId);
    public function searchByPrice($minPrice, $maxPrice);
}
