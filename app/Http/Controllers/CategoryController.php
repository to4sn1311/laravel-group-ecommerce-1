<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
class CategoryController extends Controller
{   
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        try {
            $categories = $this->categoryService->getParentWithChildrenCount();
            return view('categories.index',compact('categories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi tải danh sách danh mục.');
        }
    }
    public function create()
    {
        try {
             $categories = $this->categoryService->getAllParentCategories();
            return view('categories.create',[ 'categories' => $categories]);
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi: '.$e->getMessage());
        }
    }
    public function store(CreateCategoryRequest $request)
    {
        try {
            $this->categoryService->createCategory($request->validated());
            
            return response()->json(['message' => 'Tạo danh mục thành công.'], 200);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi tạo danh mục.');
        }
    }
    public function edit($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            $categories = $this->categoryService->getAllParentCategories();
            return view('categories.edit', compact('category','categories'));
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Không tìm thấy danh mục.');
        }
    }
    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            $this->categoryService->updateCategory($id, $request->validated());
            return response()->json(['message' => 'Cập nhật danh mục thành công.'], 200);
        }  catch (\Exception $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $this->categoryService->deleteCategory($id);
            return response()->json(['message' => 'Danh mục đã được xóa thành công!']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
    public function show($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            $categories=$this->categoryService->getChildren($id);
            return view('categories.show', compact('category','categories'));
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Không tìm thấy danh mục.');
        }
    }
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $categories = $this->categoryService->searchCategories($keyword);
        return response()->json([
            'categories' => $categories->items(),
            'pagination' => (string) $categories->links()
        ]);
    }
    public function searchChildren(Request $request, $parentId)
    {
        $keyword = $request->input('keyword');
        $categories = $this->categoryService->searchChildCategories($keyword,$parentId);
        return response()->json([
            'categories' => $categories->items(),
            'pagination' => (string) $categories->links()
        ]);
    }
}
