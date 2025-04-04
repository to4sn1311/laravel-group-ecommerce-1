<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Quản lý người dùng - sử dụng middleware permission
    Route::middleware('permission:user-list')->get('/users', [UserController::class, 'index'])->name('users.index');
    Route::middleware('permission:user-create')->get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::middleware('permission:user-create')->post('/users', [UserController::class, 'store'])->name('users.store');
    Route::middleware('permission:user-list')->get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::middleware('permission:user-edit')->get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::middleware('permission:user-edit')->put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::middleware('permission:user-delete')->delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Quản lý vai trò - sử dụng middleware permission
    Route::middleware('permission:role-list')->get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::middleware('permission:role-create')->get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::middleware('permission:role-create')->post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::middleware('permission:role-list')->get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::middleware('permission:role-edit')->get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::middleware('permission:role-edit')->put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::middleware('permission:role-delete')->delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
});

require __DIR__.'/auth.php';
//gom+permi
Route::get('/categories',[CategoryController::class,'index'])
    ->name('categories.index');

Route::post('/categories',[CategoryController::class,'store'])
->name('categories.store')
->middleware('auth');
Route::get('/categories/create',[CategoryController::class,'create'])
    ->name('categories.create')
    ->middleware('auth');
//edit
Route::get('/categories/{id}', [CategoryController::class, 'edit'])->name('categories.edit')->middleware('auth');
Route::put('/categories/{id}',[CategoryController::class,'update'])
->name('categories.update')
->middleware('auth');

//delete    
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy')
->middleware('auth');

//show
Route::get('/categories/{id}/show', [CategoryController::class, 'show'])->name('categories.show')->middleware('auth');
