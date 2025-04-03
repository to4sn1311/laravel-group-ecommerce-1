<?php

namespace App\Repositories\Interfaces;

use App\Repositories\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function findByName(string $name);
    public function findByCategoryId(int $categoryId);
    public function searchByPrice($minPrice, $maxPrice);

}
