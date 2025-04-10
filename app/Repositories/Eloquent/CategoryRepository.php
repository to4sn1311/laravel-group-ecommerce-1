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
        return $this->model->whereNull('parent_id')->get();
    }
    public function getParentWithChildrenCount(){
        return $this->model->whereNull('parent_id')
        ->withCount('children')
        ->paginate(10);
    } 
    public function getChildren($id){
        return $this->model->whereNotNull('parent_id')->where('parent_id', $id) ->paginate(10);
    } 
    public function searchCategories($keyword)
    {
        return Category::where('name', 'like', "%$keyword%")
            ->whereNull('parent_id')
            ->withCount('children')
            ->paginate(10);
    }
    public function searchChildCategories($keyword,$id)
    {
        return Category::where('parent_id', $id)
        ->where('name', 'like', "%$keyword%")
        ->paginate(10);
    }

}
