<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
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
    }

}
