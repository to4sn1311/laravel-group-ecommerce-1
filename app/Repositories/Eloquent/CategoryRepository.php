<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
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
    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    // public function getAll()
    // {
    //     return $this->model->latest('id')->paginate(10);
    // }
    public function getAllParent(){
        return $this->model->whereNull('parent_id')->get();
    }

    public function getParentWithChildrenCount(){
        return $this->model->whereNull('parent_id')
        ->withCount('children')
        ->paginate(10);
    }

    /**
     * @param int $id
     * @return Category
     */
    // public function findById(int $id)
    // {
    //     return $this->model->findOrFail($id);
    // }

    // /** 
    //  * @param array $data
    //  * @return Category
    //  */
    // public function create(array $data)
    // {
    //     if ($data['parent_id'] === 'null') {
    //         $data['parent_id'] = null;
    //     }        
    //     return $this->model->create($data);
    // }

    // /** 
    //  * @param int $id
    //  * @param array $data
    //  * @return bool
    //  */
    // public function update($id, array $data)
    // {
    //     $category = $this->findById($id);
    //     if ($data['parent_id'] === 'null') {
    //         $data['parent_id'] = null;
    //     }          

    //     return $category->update($data);
    // }

    // /**
    //  * @param int $id
    //  * @return bool
    //  */
    // public function delete($id)
    // {
    //     $category = $this->findById($id);
    //     return $category->delete();
    // }

    
}
