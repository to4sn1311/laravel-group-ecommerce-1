<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'stock',

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

    public function getImagePathAttribute()
    {
        return asset('images/upload/' . $this->image);
    }

    public function scopeFilter($query, $filters)
    {
        if (empty($filters['keyword']) && empty($filters['price_range'])) {
            return $query;
        }

        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhereHas('categories', function ($cat) use ($keyword) {
                        $cat->where('name', 'like', "%{$keyword}%");
                    });
            });
        }
        if (!empty($filters['price_range'])) {
            [$min, $max] = explode('-', $filters['price_range']);
            $query->whereBetween('price', [(float)$min, (float)$max]);
        }
        return $query;
    }
}
