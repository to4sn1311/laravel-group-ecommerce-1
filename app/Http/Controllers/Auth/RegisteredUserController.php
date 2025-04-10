<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $messages = [
            'name.required' => 'Tên không được để trống',
            'name.string' => 'Tên phải là chuỗi ký tự',
            'name.max' => 'Tên không được vượt quá :max ký tự',
            'email.required' => 'Email không được để trống',
            'email.string' => 'Email phải là chuỗi ký tự',
            'email.lowercase' => 'Email phải viết thường',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được vượt quá :max ký tự',
            'email.unique' => 'Email đã được sử dụng bởi người dùng khác',
            'email.regex' => 'Email phải có định dạng @deha-soft.com',
            'password.required' => 'Mật khẩu không được để trống',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự',
            'password.mixed' => 'Mật khẩu phải chứa ít nhất một chữ hoa và một chữ thường',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất một số',
            'password.symbols' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt',
            'password.uncompromised' => 'Mật khẩu đã bị rò rỉ trong một vụ rò rỉ dữ liệu. Vui lòng chọn mật khẩu khác.',
        ];

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'regex:/^[\w.+-]+@deha-soft\.com$/i'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], $messages);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $userRole = Role::where('name', 'User')->first();
        if ($userRole) {
            $user->roles()->attach($userRole->id);
        }

        event(new Registered($user));

        Auth::login($user);

        if ($user->roles->count() === 1 && $user->hasRole('User')) {
            return redirect(route('client.index', absolute: false));
        }

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages():array {
        return [
            'email.regex' => 'Email phải có định dạng @deha-soft.com',
        ];
    }

}
