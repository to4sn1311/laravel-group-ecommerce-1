<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    protected $model;

    /**
   * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
      return $this->model->whereDoesntHave('roles', function($query) {
          $query->where('name', 'Super Admin');
      })->get();
    }

    /**
     * Tìm kiếm và phân trang người dùng
     *
     * @param string|null $search
     * @param int $perPage
     * @param string|array|null $roles
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search($search = null, $roles = null, $perPage = 10)
    {
        // Sử dụng scope withRole đã được định nghĩa trong model User
        $query = $this->model->with('roles')->withRole($roles);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        return $query->paginate($perPage);
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        return $this->model->create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data)
    {
        $user = $this->findById($id);

        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $user->update($data);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        $user = $this->findById($id);
        return $user->delete();
    }

    /**
     * @param int $userId
     * @param array $roleIds
     * @return void
     */
    public function syncRoles(int $userId, array $roleIds)
    {
        $user = $this->findById($userId);
        $user->roles()->sync($roleIds);
    }
}
