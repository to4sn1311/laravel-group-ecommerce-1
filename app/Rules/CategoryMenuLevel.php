<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CategoryMenuLevel implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value) { // Chỉ kiểm tra nếu có parent_id
            $parentCategory = Category::find($value);

            if (!$parentCategory) {
                $fail('Danh mục cha không tồn tại.');
                return;
            }

            if ($parentCategory->parent_id !== null) { // Nếu danh mục cha đã có parent_id, tức là cấp 2
                $fail('Không thể thêm danh mục cấp 3.');
            }
        }
    }
}
