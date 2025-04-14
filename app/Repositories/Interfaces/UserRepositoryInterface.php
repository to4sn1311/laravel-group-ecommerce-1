<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Lấy tất cả người dùng
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll();
    
    /**
     * Tìm kiếm và phân trang người dùng
     * 
     * @param string|null $search
     * @param int $perPage
     * @param string|array|null $roles
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search($search = null, $roles = null, $perPage = 10);
    
    /**
     * Lấy một người dùng theo ID
     * 
     * @param int $id
     * @return User|null
     */
    public function findById(int $id);
    
    /**
     * Tạo người dùng mới
     * 
     * @param array $data
     * @return User
     */
    public function create(array $data);
    
    /**
     * Cập nhật thông tin người dùng
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data);
    
    /**
     * Xóa người dùng
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id);
    
    /**
     * Gán vai trò cho người dùng
     * 
     * @param int $userId
     * @param array $roleIds
     * @return void
     */
    public function syncRoles(int $userId, array $roleIds);
}
