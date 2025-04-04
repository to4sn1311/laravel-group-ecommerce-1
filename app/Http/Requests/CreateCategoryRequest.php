<?php

namespace App\Http\Requests;

use App\Rules\CategoryMenuLevel;
use Illuminate\Foundation\Http\FormRequest;
use phpDocumentor\Reflection\Types\Nullable;

class CreateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
             'name'=>'required|unique:categories',
             'parent_id' => ['nullable', 'exists:categories,id', new CategoryMenuLevel()],
            ];
    }
}
