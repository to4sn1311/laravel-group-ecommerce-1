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
}
