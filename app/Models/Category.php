<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
    ];

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get the products in this category.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * scope danh mục cấp 1
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * scope danh mục cấp 2
     */
    public function scopeChild($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * scope tìm kiếm theo tên
     */
    public function scopeSearchByName($query, $keyword)
    {
        return $query->where('name', 'like', "%$keyword%");
    }

    /**
     * scope tìm kiếm theo id danh mục cấp 1
     */
    public function scopeOfParent($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }
}
