<?php

namespace App\Repositories\Interfaces;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllWithCategories(): Collection;
    public function search(array $filters, $perPage = 5);
}
