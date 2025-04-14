<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CategoryMenuLevel implements ValidationRule
{
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

        // Kiểm tra nếu có parent_id
        $parent = Category::find($value);
        if (!$parent) {
            $fail('Danh mục cha không tồn tại.');
            return;
        } elseif ($parent->parent_id !== null) {
            $fail('Không thể thêm danh mục cấp 3.');
        }
    }
}
