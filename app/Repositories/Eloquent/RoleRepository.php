<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function findByName(string $name)
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Tìm kiếm và phân trang vai trò
     * 
     * @param string|null $search
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search($search = null, $perPage = 10)
    {
        $query = $this->model->where('name', '!=', 'Super Admin');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }
}
