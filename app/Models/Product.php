<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'stock',
        'category_id',
    ];

    /**
     * Get the primary category for the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all categories for the product.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
