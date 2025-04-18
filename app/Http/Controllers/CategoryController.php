<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
            return view('categories.index', compact('categories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi tải danh sách danh mục.');
        }
    }

    public function create()
    {
        try {
             $categories = $this->categoryService->getAllParentCategories();
            return view('categories.create', compact('categories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi: '.$e->getMessage());
        }
    }

    public function store(CreateCategoryRequest $request)
    {
        try {
            $this->categoryService->createCategory($request->validated());
            return response()->json(['message' => 'Tạo danh mục thành công.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
    
    public function edit($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            $categories = $this->categoryService->getAllParentCategories();
            $is_parent = $this->categoryService->isParent($id);
            return view('categories.edit', compact('category', 'categories', 'is_parent'));
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Không tìm thấy danh mục.');
        }
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            $this->categoryService->updateCategory($id, $request->validated());
            return response()->json(['message' => 'Cập nhật danh mục thành công.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->categoryService->deleteCategory($id);
            return response()->json(['message' => 'Danh mục đã được xóa thành công!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Danh mục cần xóa không hợp lệ.'.$e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Lấy danh sách tất cả các danh mục.
     */
    public function show($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            $categories=$this->categoryService->getChildren($id);
            return view('categories.show', compact('category', 'categories'));
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Không tìm thấy danh mục.');
        }
    }

    /**
     * Tìm kiếm danh mục cấp 1
     */
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $categories = $this->categoryService->searchCategories($keyword);
        return $this->jsonPaginatedResponse($categories);
    }

    /**
     * Tìm kiếm danh mục cấp 2
     */
    public function searchChildren(Request $request, $parentId)
    {
        $keyword = $request->input('keyword');
        $categories = $this->categoryService->searchChildCategories($keyword, $parentId);
        return $this->jsonPaginatedResponse($categories);
    }

    private function jsonPaginatedResponse($categories)
    {
        return response()->json([
            'categories' => $categories->items(),
            'pagination' => (string) $categories->links()
        ]);
    }
}
