<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Kiểm tra nếu người dùng đã đăng nhập và có quyền tạo người dùng
        if (Auth::check() && method_exists(Auth::user(), 'hasPermission')) {
            return Auth::user()->hasPermission('user-create');
        }

        // Cho phép nếu không thể kiểm tra quyền
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
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[\w.+-]+@deha-soft\.com$/i', // Chỉ cho phép email có domain @deha-soft.com
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => ['required', 'array']
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
            'name.required' => 'Tên người dùng không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được sử dụng bởi người dùng khác',
            'email.regex' => 'Email phải có định dạng @deha-soft.com',
            'password.required' => 'Mật khẩu không được để trống',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'roles.required' => 'Vui lòng chọn ít nhất một vai trò'
        ];
    }
}
