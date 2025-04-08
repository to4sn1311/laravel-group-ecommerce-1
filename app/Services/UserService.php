<?php

namespace App\Services;

use App\Models\Role;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsers()
    {
        return $this->userRepository->getAll();
    }

    /**
     * Tìm kiếm và phân trang người dùng
     * 
     * @param string|null $search
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchUsers($search = null, $perPage = 10)
    {
        return $this->userRepository->search($search, $perPage);
    }

    /**
     * @param int $id
     * @return \App\Models\User
     */
    public function getUserById(int $id)
    {
        return $this->userRepository->findById($id);
    }

    /**
     * @param array $data
     * @return \App\Models\User
     */
    public function createUser(array $data)
    {
        try {
            DB::beginTransaction();
            
            $user = $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password']
            ]);
            
            // Gán vai trò cho người dùng
            if (isset($data['roles'])) {
                $this->userRepository->syncRoles($user->id, $data['roles']);
            }
            
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateUser(int $id, array $data)
    {
        try {
            DB::beginTransaction();
            
            // Cập nhật thông tin người dùng
            $userData = [
                'name' => $data['name'],
                'email' => $data['email']
            ];
            
            // Thêm mật khẩu nếu có
            if (isset($data['password']) && !empty($data['password'])) {
                $userData['password'] = $data['password'];
            }
            
            $this->userRepository->update($id, $userData);
            
            // Cập nhật vai trò cho người dùng
            if (isset($data['roles'])) {
                $this->userRepository->syncRoles($id, $data['roles']);
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id)
    {
        try {
            DB::beginTransaction();
            
            // Xóa người dùng
            $this->userRepository->delete($id);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRoles()
    {
        return Role::all();
    }
}