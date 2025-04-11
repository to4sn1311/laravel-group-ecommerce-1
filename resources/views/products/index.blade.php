<x-app-layout>
    <div class="py-12">

        <!-- Create Blog Button -->
        <div class=" max-w-7xl mx-auto flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">📜 Product</h2>
            <button id="createProductBtn" class="btn-create px-4 py-2 bg-blue-600 text-white text-sm border border-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
                ➕ Create New Product
            </button>
        </div>

        <div class=" max-w-7xl mx-auto flex justify-between items-center mb-6 gap-4">
            <form id="search-form" class="w-full flex gap-4">
                <div class="w-full flex-1">
                    <input type="text" id="search-name" name="keyword" value="{{ $filters['keyword'] ?? '' }}" placeholder="{{ __('Tìm kiếm theo tên hoặc danh mục sản phẩm') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-black-900 dark:text-gray-100 bg-white dark:bg-gray-700">
                </div>
                <div class="w-full flex-1">
                    <select id="price-range" class="rounded-lg text-black-900 dark:text-gray-100 bg-white dark:bg-gray-700">
                        <option value="">-- Chọn khoảng giá --</option>
                        <option value="0-500000">Dưới 500K</option>
                        <option value="500000-1000000">500K - 1 triệu</option>
                        <option value="1000000-3000000">1 triệu - 3 triệu</option>
                        <option value="3000000-999999999">Trên 3 triệu</option>
                    </select>
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    {{ __('Tìm kiếm') }}
                </button>
            </form>
        </div>

        <!-- Blog Table -->
        <div class="max-w-7xl mx-auto shadow-lg border border-white rounded-lg overflow-hidden">
            <table class="w-full text-white">
                <thead class="uppercase text-sm">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Price</th>
                        <th class="px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody id="productTableBody" class="divide-y divide-white">

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="paginationLinks" class="flex justify-center mt-4 gap-2"></div>
    </div>

    @include('products.modal')
    @include('products.show')
</x-app-layout>

@vite(['resources/js/products.js'])