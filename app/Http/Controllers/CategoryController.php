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
    // Lấy danh mục cha, phân trang 5 cái
    $categories = Category::whereNull('parent_id')->paginate(5);

    // Lấy danh mục con của tất cả danh mục cha đã lấy
    $categoryIds = $categories->pluck('id');
    $children = Category::whereIn('parent_id', $categoryIds)->get()->groupBy('parent_id');

    return view('categories.index', compact('categories', 'children'));
}

    /*
    public function index()
    {//trycatch
        $categories = $this->categoryService->getAllCategories();
        return view('categories.index',compact('categories'));
    }*/
    public function create()
    {
        $categories = $this->categoryService->getAllParentCategories();
        /*
        $categories = $categories->filter(function($c){
            return $c->parent_id == null;
        });
*/
        return view('categories.create',[ 'categories' => $categories]);
    }
    public function store(CreateCategoryRequest $request){

        $this->categoryService->createCategory($request->validated());

        return redirect()->route('categories.index');
    
    }
    public function edit($id)
    {
        $category = $this->categoryService->getCategoryById($id);
        $categories = $this->categoryService->getAllParentCategories();
        return view('categories.edit', compact('category','categories'));
    }
    public function update(UpdateCategoryRequest $request, $id)
    {
        $this->categoryService->updateCategory($id, $request->validated());
        return redirect()->route('categories.index');
    }
    public function destroy($id)
    {
        $this->categoryService->deleteCategory($id);
        return redirect()->route('categories.index');
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
