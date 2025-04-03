<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try {
            return method_exists(Auth::user(), 'hasPermission') && Auth::user()->hasPermission('role-create');
        } catch (\Exception $e) {
            return true; // Cho phép nếu không thể kiểm tra quyền
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'permissions' => 'required|array'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên vai trò không được để trống',
            'name.unique' => 'Tên vai trò đã tồn tại',
            'permissions.required' => 'Vui lòng chọn ít nhất một quyền'
        ];
    }
}
