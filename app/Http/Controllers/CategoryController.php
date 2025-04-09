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
            //return redirect()->route('categories.index');
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
            //return redirect()->route('categories.index');
        }  catch (\Exception $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Danh mục không tồn tại.'], 404);
        }
        try {
            $this->categoryService->deleteCategory($id);
            return response()->json(['message' => 'Danh mục đã được xóa thành công!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể xóa danh mục!'], 500);
        }
    }
    public function show($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            $categories=Category::whereNotNull('parent_id')->where('parent_id', $id) ->paginate(10);
            return view('categories.show', compact('category','categories'));
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('message', 'Không tìm thấy danh mục.');
        }
    }
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $categories = Category::where('name', 'like', "%$keyword%")
            ->whereNull('parent_id')
            ->withCount('children')
            ->paginate(10);

        return response()->json([
            'categories' => $categories->items(),
            'pagination' => (string) $categories->links()
        ]);
    }

    public function searchChildren(Request $request, $parentId)
    {
        $keyword = $request->input('keyword');
    
        $categories = Category::where('parent_id', $parentId)
            ->where('name', 'like', "%$keyword%")
            ->paginate(10);
    
        return response()->json([
            'categories' => $categories->items(),
            'pagination' => (string) $categories->links()
        ]);
    }
    
    /*
    protected $category;
    public function __construct(Category $category) {
        $this->category = $category;
    }

    public function index(){
        $categories=$this->category->latest('id')->paginate(10);
        return view('categories.index',compact('categories'));
    }
    public function store(CreateCategoryRequest $request){
    $data = $request->all();
    if ($data['parent_id'] === 'null') {
        $data['parent_id'] = null;
    }
    $this->category->create($data);        
    return redirect()->route('categories.index');
    }
    public function create(){
        $categories = Category::whereNull('parent_id')->get();
        return view('categories.create',[ 'categories' => $categories]);
    }
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::whereNull('parent_id')->get();
        return view('categories.edit', compact('category','categories'));
    }
    public function update(UpdateCategoryRequest $request, $id)
    {
        Category::findOrFail($id)->update($request->all());
        return redirect()->route('categories.index');
    }
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->route('categories.index');
    }*/

}
