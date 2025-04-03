<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Kiểm tra quyền xem danh sách người dùng
            if (method_exists(Auth::user(), 'hasPermission') && !Auth::user()->hasPermission('user-list')) {
                return redirect()->route('dashboard')->with('error', 'Bạn không có quyền xem danh sách người dùng');
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi và tiếp tục
        }

        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // Kiểm tra quyền tạo người dùng
            if (method_exists(Auth::user(), 'hasPermission') && !Auth::user()->hasPermission('user-create')) {
                return redirect()->route('users.index')->with('error', 'Bạn không có quyền tạo người dùng mới');
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi và tiếp tục
        }

        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // Dữ liệu đã được xác thực trong StoreUserRequest
        
        // Tạo người dùng mới
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Gán vai trò cho người dùng
        $user->roles()->attach($request->roles);

        return redirect()->route('users.index')
            ->with('success', 'Người dùng đã được tạo thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Kiểm tra quyền xem người dùng
            if (method_exists(Auth::user(), 'hasPermission') && !Auth::user()->hasPermission('user-list')) {
                return redirect()->route('users.index')->with('error', 'Bạn không có quyền xem thông tin người dùng');
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi và tiếp tục
        }

        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            // Kiểm tra quyền chỉnh sửa người dùng
            if (method_exists(Auth::user(), 'hasPermission') && !Auth::user()->hasPermission('user-edit')) {
                return redirect()->route('users.index')->with('error', 'Bạn không có quyền chỉnh sửa người dùng');
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi và tiếp tục
        }

        $user = User::findOrFail($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        // Dữ liệu đã được xác thực trong UpdateUserRequest
        
        $user = User::findOrFail($id);

        // Cập nhật thông tin người dùng
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        // Cập nhật vai trò
        $user->roles()->sync($request->roles);

        return redirect()->route('users.index')
            ->with('success', 'Người dùng đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Kiểm tra quyền xóa người dùng
            if (method_exists(Auth::user(), 'hasPermission') && !Auth::user()->hasPermission('user-delete')) {
                return redirect()->route('users.index')->with('error', 'Bạn không có quyền xóa người dùng');
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi và tiếp tục
        }

        // Không cho phép xóa chính mình
        if (Auth::id() == $id) {
            return redirect()->route('users.index')->with('error', 'Bạn không thể xóa tài khoản của chính mình');
        }

        $user = User::findOrFail($id);
        $user->roles()->detach(); // Xóa quan hệ với vai trò
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Người dùng đã được xóa thành công');
    }
}
