<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quản lý Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                    @endif

                    <!-- Thông tin cơ bản -->
                    <div class="mb-8 p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('Thông tin Parent Category') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-between">
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Tên:') }}</p>
                                <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $category->name }}</p>
                            </div>                            
                        </div>
                    </div>
                    <div class="mb-8">
                        <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">{{ __('Danh sách Child Category') }}</h2>
                        
                            <div class="mb-4">
                                <input type="text" id="search-child-category" placeholder="🔍 Nhập tên child category..."
                                    class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse rounded-lg shadow-sm bg-white dark:bg-gray-700">
                                    <thead>
                                        <tr class="bg-blue-500 text-white">
                                            <th class="py-3 px-4 text-left">{{ __('Tên') }}</th>
                                            <th class="py-3 px-4 text-center">{{ __('Hành động') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="child-category-list">
                                        @foreach($categories as $category)
                                        <tr>
                                            <td class="py-3 px-4">{{ $category->name }}</td>
                                            <td class="py-3 px-4 text-center">
                                                @if(Auth::user()->hasPermission('category-edit'))
                                                <a href="{{ route('categories.edit', $category->id) }}" 
                                                    class="inline-flex items-center px-3 py-1 ml-2 text-gray-900 bg-white border border-gray-300 rounded-lg shadow-md hover:bg-gray-100 transition">
                                                    ✏️ Sửa
                                                </a>
                                                @endif
                                                @if(Auth::user()->hasPermission('category-delete'))
                                                    <button data-id="{{ $category->id }}" 
                                                            class="inline-flex items-center px-3 py-1 ml-2 text-white bg-red-500 rounded-lg shadow-md hover:bg-red-600 transition delete-category">
                                                        🗑️ Xóa
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="pagination-links" class="mt-6">
                                {{ $categories->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $(document).on("click", ".delete-category", function () {
                let categoryId = $(this).data("id");
                if (!confirm("Bạn có chắc muốn xóa danh mục này?")) return;

                $.ajax({
                    url: `/categories/${categoryId}`, 
                    type: "DELETE",
                    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                    success: function (response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function (xhr) {
                        alert("Có lỗi xảy ra: " + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            let debounceTimer;
            let parentId = window.location.pathname.split("/").pop(); // Lấy ID của danh mục cha

            function fetchChildCategories(url = `/categories/${parentId}/search-children`, keyword = "") {
                $.ajax({
                    url: url,
                    type: "GET",
                    data: { keyword: keyword },
                    success: function (response) {
                        let rows = "";
                        response.categories.forEach(category => {
                            rows += `
                                <tr>
                                    <td class="py-3 px-4">${category.name}</td>
                                    <td class="py-3 px-4 text-center">
                                        @if(Auth::user()->hasPermission('category-edit'))
                                            <a href="/categories/${category.id}/edit" 
                                                class="inline-flex items-center px-3 py-1 ml-2 text-gray-900 bg-white border border-gray-300 rounded-lg shadow-md hover:bg-gray-100 transition">
                                                ✏️ Sửa
                                            </a>
                                        @endif
                                        @if(Auth::user()->hasPermission('category-delete'))
                                            <button data-id="${category.id}"  
                                                    class="inline-flex items-center px-3 py-1 ml-2 text-white bg-red-500 rounded-lg shadow-md hover:bg-red-600 transition delete-category">
                                                🗑️ Xóa
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            `;
                        });

                        $("#child-category-list").html(rows);
                        $("#pagination-links").html(response.pagination);
                    },
                    error: function () {
                        alert("Có lỗi xảy ra khi tải dữ liệu.");
                    }
                });
            }

            // Lắng nghe sự kiện tìm kiếm
            $("#search-child-category").on("input", function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    let keyword = $(this).val();
                    fetchChildCategories(`/categories/${parentId}/search-children`, keyword);
                }, 500);
            });

            // Xử lý sự kiện click vào phân trang
            $(document).on("click", "#pagination-links a", function (e) {
                e.preventDefault();
                let url = $(this).attr("href");
                let keyword = $("#search-child-category").val();
                fetchChildCategories(url, keyword);
            });

            // Load danh sách ban đầu khi chưa tìm kiếm
            fetchChildCategories();
        });
    </script>
</x-app-layout> 