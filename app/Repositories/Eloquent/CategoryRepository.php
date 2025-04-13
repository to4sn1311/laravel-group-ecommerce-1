<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    protected $model;
    public function __construct(Category $model)
    {
        $this->model = $model;
    }
    public function getAllParent(){
        return $this->model->parent()->get();
    }
    public function getParentWithChildrenCount(){
        return $this->model->parent()
        ->withCount('children');
    } 
    public function getChildren($id){
        return $this->model->child()
        ->ofParent($id);
    } 
    public function searchCategories($keyword)
    {
        return $this->model->SearchByName($keyword)
        ->parent()
        ->withCount('children');
    }
    public function searchChildCategories($keyword,$id)
    {
        return $this->model->child()
        ->ofParent($id)
        ->searchByName($keyword);
    }
}
