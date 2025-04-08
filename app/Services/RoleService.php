<?php

namespace App\Services;

use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleService
{
     protected $roleRepository;
     public function __construct(RoleRepositoryInterface $roleRepository)
     {
        $this->roleRepository = $roleRepository;
     }
    public function getAllRoles()
    {
        return $this->roleRepository->all();
    }

    /**
     * Tìm kiếm và phân trang vai trò
     * 
     * @param string|null $search
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchRoles($search = null, $perPage = 10)
    {
        return $this->roleRepository->search($search, $perPage);
    }
}
