<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use App\Repositories\BaseRepositoryInterface;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllParent();
    public function getParentWithChildrenCount();
    public function getChildren($id);
    public function searchCategories($keyword);
    public function searchChildCategories($keyword,$id);

}
