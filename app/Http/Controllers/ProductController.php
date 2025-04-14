<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Services\ProductService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        try {
            $filters = [
                'keyword' => $request->input('keyword'),
                'price_range' => $request->input('price_range')
            ];
            $products = $this->productService->search($filters);
            $categories = Category::select('id', 'name')->get();
            if (request()->ajax()) {
                return response()->json([
                    'products' => $products->items(),
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'last_page' => $products->lastPage(),
                        'next_page_url' => $products->nextPageUrl(),
                        'prev_page_url' => $products->previousPageUrl(),
                        'total' => $products->total(),
                    ],
                ]);
            }
            return view('products.index', compact('products', 'categories'));
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading products: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error loading products');
        }
    }


    // public function create()
    // {
    //     $categories = Category::select('id', 'name')->get();
    //     // if (request()->ajax()) {
    //     //     return response()->json([
    //     //         'categories' => $categories
    //     //     ]);
    //     // }
    //     return view('products.modal', compact('categories'));
    // }


    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            $product = $this->productService->create($data);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Sản phẩm đã được tạo thành công.', 'data' => $product]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }


    public function show(string $id)
    {
        try {
            $product = $this->productService->find($id);
            return response()->json(['success' => true, 'message' => 'Sản phẩm đã được hiển thị', 'data' => $product]);
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('message', 'Không tìm thấy danh mục.');
        }
    }

    public function edit(string $id)
    {
        try {
            $product = $this->productService->find($id);
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'description' => $product->description,
                    'image' => $product->image_path,
                    'categories' => $product->categories->map(function ($category) {
                        return [
                            'id' => $category->id,
                            'name' => $category->name,
                        ];
                    }),
                ],
            ]);
        } catch (Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm',
                ], 404);
            }
            return back()->with('error', 'Không tìm thấy sản phẩm');
        }
    }


    public function update(UpdateProductRequest  $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $data['categories'] = $request->input('categories', []);
            $updated = $this->productService->update($id, $data);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Sản phẩm đã được sửa thành công.', 'data' => $updated]);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
        }
    }


    public function destroy(string $id)
    {
        try {
            $deleted = $this->productService->delete($id);
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sản phẩm đã được xóa thành công'
                ]);
            }
            return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa!');
        } catch (Exception $e) {
            return back()->with('error', 'Lỗi khi xóa sản phẩm: ' . $e->getMessage());
        }
    }
}
