<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\Response;


class ProductController extends Controller
{

    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        try {
            $products = $this->productService->getAll();
            $categories = Category::whereNotNull('parent_id')->get();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'products' => $products,
                    'categories' => $categories
                ]);
            }
            return view('products.index');
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


    public function create()
    {
        $categories = Category::whereNotNull('parent_id')->get();
        if (request()->ajax()) {
            return response()->json([
                'categories' => $categories
            ]);
        }
    }


    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->productService->create($request->validated());
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sản phẩm đã được tạo thành công.',
                    'product' => $product
                ]);
            }
            return redirect()->route('products.index')
                ->with('success', 'Sản phẩm đã được tạo thành công');
        } catch (Exception $e) {
            return response()->json(['error' => 'Lỗi khi tạo sản phẩm!'], 500);
        }
    }


    public function show(string $id)
    {
        $product = $this->productService->find($id);
        if (!$product) {
            return response()->json(['error' => 'Không tìm thấy sản phẩm.'], Response::HTTP_NOT_FOUND);
        }
        return view('products.show', compact('product'));
    }

    public function edit(string $id)
    {
        try {
            $product = $this->productService->find($id);
            $categories = Category::all();
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'product' => $product,
                    'categories' => $categories
                ]);
            }

            $categories = Category::whereNotNull('parent_id')->get();
            return view('products.edit', compact('product', 'categories'));
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
            $updated = $this->productService->update($id, $request->validated());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sản phẩm đã được cập nhật thành công.',
                    'product' => $updated
                ]);
            }
            return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật!');
        } catch (Exception $e) {
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
