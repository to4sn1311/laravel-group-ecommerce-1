<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

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

        $users = $this->userService->getAllUsers();
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

        $roles = $this->userService->getAllRoles();
        $roles = $roles->filter(function($roles){
            return $roles->name !== 'Super Admin';
        });

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // Dữ liệu đã được xác thực trong StoreUserRequest
        try {
            $this->userService->createUser($request->validated());
            return redirect()->route('users.index')
                ->with('success', 'Người dùng đã được tạo thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            if (method_exists(Auth::user(), 'hasPermission') && !Auth::user()->hasPermission('user-list')) {
                return redirect()->route('users.index')->with('error', 'Bạn không có quyền xem thông tin người dùng');
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi và tiếp tục
        }

        try {
            $user = $this->userService->getUserById($id);
            return view('users.show', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Không tìm thấy người dùng');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            if (method_exists(Auth::user(), 'hasPermission') && !Auth::user()->hasPermission('user-edit')) {
                return redirect()->route('users.index')->with('error', 'Bạn không có quyền chỉnh sửa người dùng');
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi và tiếp tục
        }

        try {
            $user = $this->userService->getUserById($id);
            $roles = $this->userService->getAllRoles();
            $userRoles = $user->roles->pluck('id')->toArray();
            
            return view('users.edit', compact('user', 'roles', 'userRoles'));
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Không tìm thấy người dùng');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $this->userService->updateUser($id, $request->validated());
            return redirect()->route('users.index')
                ->with('success', 'Người dùng đã được cập nhật thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if (method_exists(Auth::user(), 'hasPermission') && !Auth::user()->hasPermission('user-delete')) {
                return redirect()->route('users.index')->with('error', 'Bạn không có quyền xóa người dùng');
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi và tiếp tục
        }

        if (Auth::id() == $id) {
            return redirect()->route('users.index')->with('error', 'Bạn không thể xóa tài khoản của chính mình');
        }

        try {
            $this->userService->deleteUser($id);
            return redirect()->route('users.index')
                ->with('success', 'Người dùng đã được xóa thành công');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
}
