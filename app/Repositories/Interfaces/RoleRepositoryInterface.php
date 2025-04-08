<?php

namespace App\Repositories\Interfaces;

use App\Repositories\BaseRepositoryInterface;

interface RoleRepositoryInterface extends  BaseRepositoryInterface
{
    public function findByName(string $name);

    /**
     * Tìm kiếm và phân trang vai trò
     * 
     * @param string|null $search
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search($search = null, $perPage = 10);
}
