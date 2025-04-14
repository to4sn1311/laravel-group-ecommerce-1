<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $perPage = $request->input('per_page', 10);

        $users = $this->userService->searchUsers($search, $role, $perPage);

        // Lấy tất cả roles và loại bỏ Super Admin
        $roles = $this->userService->getAllRoles(true);

        return view('users.index', compact('users', 'search', 'role', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Lấy tất cả roles và loại bỏ Super Admin
        $roles = $this->userService->getAllRoles(true);

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $this->userService->createUser($request->validated());
            return redirect()->route('users.index')
                ->with('success', 'Người dùng đã được tạo thành công');
        } catch (\Exception $exception) {
            Log::error('Error creating user: ' . $exception->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi: ' . $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = $this->userService->getUserById($id);
            return view('users.show', compact('user'));
        } catch (\Exception $exception) {
            Log::error('User not found: ' . $exception->getMessage());

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
            $user = $this->userService->getUserById($id);

            // Lấy tất cả roles và loại bỏ Super Admin
            $roles = $this->userService->getAllRoles(true);
            $userRoles = $user->roles->pluck('id')->toArray();

            return view('users.edit', compact('user', 'roles', 'userRoles'));
        } catch (\Exception $exception) {
            Log::error('User not found: ' . $exception->getMessage());

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
        } catch (\Exception $exception) {
            Log::error('Error updating user: ' . $exception->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi: ' . $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::id() == $id) {
            return redirect()->route('users.index')->with('error', 'Bạn không thể xóa tài khoản của chính mình');
        }

        try {
            $this->userService->deleteUser($id);
            return redirect()->route('users.index')
                ->with('success', 'Người dùng đã được xóa thành công');
        } catch (\Exception $exception) {
            Log::error('Error deleting user: ' . $exception->getMessage());

            return redirect()->route('users.index')
                ->with('error', 'Đã xảy ra lỗi: ' . $exception->getMessage());
        }
    }

    /**
     * Scope User with Role
     *
     * @param string|array $roles
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRole($query, $roles)
    {
        if (is_array($roles)) {
            return $query->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            });
        }
        return $query->whereHas('roles', function ($query) use ($roles) {
            $query->where('name', $roles);
        });
    }
}