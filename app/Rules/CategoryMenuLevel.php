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
         // Cho phép giá trị null (không có parent_id)
         if ($value === 'null') {
            return;
        }

        // Kiểm tra nếu có parent_id
        $parentCategory = Category::find($value);

        if ($parentCategory === null) {
            // Nếu danh mục cha không tồn tại, trả lỗi
            $fail('Danh mục cha không tồn tại.');
            return;
        }

        // Kiểm tra nếu danh mục cha đã có parent_id (tức là cấp 2)
        if ($parentCategory->parent_id !== null) {
            $fail('Không thể thêm danh mục cấp 3.');
        }
    }
}
