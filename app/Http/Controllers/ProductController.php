<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Services\ProductService;
use Exception;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{

    protected $productService;
    public function __construct( ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        try {
            $products = $this->productService->getAll();
            return view('products.index', compact('products'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi tải danh sách sản phẩm.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->productService->createProduct($request->validated());
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Sản phẩm đã được tạo thành công.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi tạo sản phẩm.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->productService->getById($id);
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Không tìm thấy sản phẩm.');
        }
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = $this->productService->getById($id);
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Không tìm thấy sản phẩm.');
        }
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest  $request, string $id)
    {
        DB::beginTransaction();
        try {
            $updated = $this->productService->updateProduct($id, $request->validated());

            if (!$updated) {
                return back()->with('error', 'Không tìm thấy sản phẩm.');
            }
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật!');
        }
        catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $deleted = $this->productService->deleteProduct($id);
            if (!$deleted) {
                return back()->with('error', 'Không tìm thấy sản phẩm.');
            }
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa!');
        }
        catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi xóa sản phẩm: ' . $e->getMessage());
        }
    }
}
