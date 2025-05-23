<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EditCategoryMenuLevel implements ValidationRule
{
    protected $currentId;

    public function __construct($currentId = null)
    {
        $this->currentId = $currentId;
    }

    /**
     * Kiểm tra giới hạn danh mục cấp 2.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Cho phép giá trị null (không có parent_id)
        if ($value === 'null') {
            return;
        }
        
        // N
        $is_parent = Category::where('parent_id', $this->currentId)->exists();
        // Kiểm tra nếu có parent_id
        $parent = Category::find($value);
        if (!$parent) {
            $fail('Danh mục cha không tồn tại.');
            return;
        } elseif($is_parent){
            $fail('Đây là danh mục cấp 1.');
            return;
        } elseif ($parent->parent_id !== null) {//||íparemt
            $fail('Không thể thêm danh mục cấp 3.');
            return;
        } else {
            return;
        }
    }
}
