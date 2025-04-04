<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    protected $categoryRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategories()
    {
        return $this->categoryRepository->getAll();
    }

    /**
     * @param int $id
     * @return \App\Models\Category
     */
    public function getCategoryById(int $id)
    {
        return $this->categoryRepository->findById($id);
    }

    /**
     * @param array $data
     * @return \App\Models\Category
     */
    public function createCategory(array $data)//xl qt
    {
        $category = $this->categoryRepository->create([
            'name' => $data['name'],
            'parent_id' => $data['parent_id'],
        ]);
        return $category;
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateCategory(int $id, array $data)
    {
        $categoryData = [
            'name' => $data['name'],
            'parent_id' => $data['parent_id']
        ];
        
        $this->categoryRepository->update($id, $categoryData);

        return true;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteCategory(int $id)
    {

        $this->categoryRepository->delete($id);
        return true;
    }
    public function getAllParentCategories()
    {
        return $this->categoryRepository->getAllParent();
    }

}