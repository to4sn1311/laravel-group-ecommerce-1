<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function getAllCategories()
    {
        return $this->categoryRepository->all();
    }
    public function getParentWithChildrenCount(){
        return $this->categoryRepository->getParentWithChildrenCount();
    }
 
    public function getCategoryById(int $id)
    {
        return $this->categoryRepository->find($id);
    }

    public function createCategory(array $data)//xl qt
    {
        if($data['parent_id']=='null'){
            $data['parent_id']=null;
        }
        $category = $this->categoryRepository->create([
            'name' => $data['name'],
            'parent_id' => $data['parent_id'],
        ]);
        return $category;
    }

    public function updateCategory(int $id, array $data)
    {
        if($data['parent_id']=='null'){
            $data['parent_id']=null;
        }
        $categoryData = [
            'name' => $data['name'],
            'parent_id' => $data['parent_id']
        ];
        $this->categoryRepository->update($id, $categoryData);

        return true;
    }

    public function deleteCategory(int $id)
    {

        $category = Category::find($id);
        if (!$category) {
            throw new Exception('Danh mục không tồn tại.');
        }
        $category->delete();
    }
    public function getAllParentCategories()
    {
        return $this->categoryRepository->getAllParent();
    }
    public function getChildren($id)
    {
        return $this->categoryRepository->getChildren($id);
    }

    public function searchCategories($keyword)
    {
        return $this->categoryRepository->searchCategories($keyword);
    }
    public function searchChildCategories($keyword,$id){
        return $this->categoryRepository->searchChildCategories($keyword,$id);
    }
}