<?php

namespace App\Repositories\Interfaces;

use App\Repositories\BaseRepositoryInterface;

interface RoleRepositoryInterface extends  BaseRepositoryInterface
{
    public function findByName(string $name);
}
