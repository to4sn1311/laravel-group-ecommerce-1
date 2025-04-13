<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Exception;

class CategoryService
{
    const PER_PAGE = 10;
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getParentWithChildrenCount(){
        return $this->categoryRepository->getParentWithChildrenCount()->paginate(self::PER_PAGE);
    }
 
    public function getCategoryById(int $id)
    {
        return $this->categoryRepository->find($id);
    }

    public function createCategory(array $data)//xl qt
    {
        $this->categoryRepository->create([
            'name' => $data['name'],
            'parent_id' => $data['parent_id'] === 'null' ? null : $data['parent_id'],
        ]);
        return true;
    }

    public function updateCategory(int $id, array $data)
    {
        $categoryData = [
            'name' => $data['name'],
            'parent_id' => $data['parent_id'] === 'null' ? null : $data['parent_id'],
        ];
        $this->categoryRepository->update($id, $categoryData);
        return true;
    }

    public function deleteCategory(int $id)
    {
        $this->categoryRepository->delete($id);
        return true;
    }

    public function getAllParentCategories()
    {
        return $this->categoryRepository->getAllParent();
    }

    public function getChildren($id)
    {
        return $this->categoryRepository->getChildren($id)->paginate(self::PER_PAGE);
    }

    public function searchCategories($keyword)
    {
        return $this->categoryRepository->searchCategories($keyword)->paginate(self::PER_PAGE);
    }

    public function searchChildCategories($keyword,$id){
        return $this->categoryRepository->searchChildCategories($keyword,$id)->paginate(self::PER_PAGE);
    }
}