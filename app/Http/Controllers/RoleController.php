<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $roleService;
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        try {
            // Kiểm tra quyền xem danh sách vai trò
            if (method_exists(Auth::user(), 'hasPermission') && !Auth::user()->hasPermission('role-list')) {
                return redirect()->route('dashboard')->with('error', 'Bạn không có quyền xem danh sách vai trò');
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi và tiếp tục
        }

        $roles = $this->roleService->getAllRoles();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // Kiểm tra quyền tạo vai trò
            if (method_exists(Auth::user(), 'hasPermission') && !Auth::user()->hasPermission('role-create')) {
                return redirect()->route('roles.index')->with('error', 'Bạn không có quyền tạo vai trò mới');
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi và tiếp tục
        }

        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        // Dữ liệu đã được xác thực trong StoreRoleRequest

        // Tạo vai trò mới
        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Gán quyền cho vai trò
        $role->permissions()->attach($request->permissions);

        return redirect()->route('roles.index')
            ->with('success', 'Vai trò đã được tạo thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Kiểm tra quyền xem vai trò
            if (method_exists(Auth::user(), 'hasPermission') && !Auth::user()->hasPermission('role-list')) {
                return redirect()->route('roles.index')->with('error', 'Bạn không có quyền xem thông tin vai trò');
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi và tiếp tục
        }

        $role = Role::with('permissions')->findOrFail($id);
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            // Kiểm tra quyền chỉnh sửa vai trò
            if (method_exists(Auth::user(), 'hasPermission') && !Auth::user()->hasPermission('role-edit')) {
                return redirect()->route('roles.index')->with('error', 'Bạn không có quyền chỉnh sửa vai trò');
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi và tiếp tục
        }

        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        // Dữ liệu đã được xác thực trong UpdateRoleRequest

        $role = Role::findOrFail($id);

        // Cập nhật thông tin vai trò
        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();

        // Cập nhật quyền của vai trò
        $role->permissions()->sync($request->permissions);

        return redirect()->route('roles.index')
            ->with('success', 'Vai trò đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Kiểm tra quyền xóa vai trò
            if (method_exists(Auth::user(), 'hasPermission') && !Auth::user()->hasPermission('role-delete')) {
                return redirect()->route('roles.index')->with('error', 'Bạn không có quyền xóa vai trò');
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi và tiếp tục
        }

        $role = Role::findOrFail($id);

        // Kiểm tra nếu vai trò có liên kết với người dùng
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Không thể xóa vai trò này vì đang được gán cho người dùng');
        }

        // Xóa quyền liên kết với vai trò
        $role->permissions()->detach();
        // Xóa vai trò
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Vai trò đã được xóa thành công');
    }
}
