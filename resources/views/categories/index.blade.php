<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quản lý category') }}
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

                    @if(Auth::user()->hasPermission('category-create'))
                    <div class="mb-6">
                        <a href="{{ route('categories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Thêm category mới') }}
                        </a>
                    </div>
                    @endif
                    <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-md">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">{{ __('Danh sách danh mục') }}</h2>
                    

                        <div class="mb-4">
                            <input type="text" id="search-category" placeholder="Nhập tên danh mục..."
                                class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:text-white">
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse rounded-lg shadow-sm bg-white dark:bg-gray-700">
                                <thead>
                                    <tr class="bg-blue-500 text-white">
                                        <th class="py-3 px-4 text-left">{{ __('Tên') }}</th>
                                        <th class="py-3 px-4 text-left">{{ __('Count_child') }}</th>
                                        <th class="py-3 px-4 text-center">{{ __('Hành động') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="category-list">
                                    @foreach($categories as $category)
                                    <tr>
                                        <td class="py-3 px-4">{{ $category->name }}</td>
                                        <td class="py-3 px-4">{{ $category->children_count }}</td>
                                        <td class="py-3 px-4 text-center">
                                            <a href="{{ route('categories.show', $category->id) }}" class="text-blue-500 hover:text-blue-700">Xem</a>
                                            @if(Auth::user()->hasPermission('category-edit'))
                                            <a href="{{ route('categories.edit', $category->id) }}" class="ml-2 text-green-500 hover:text-green-700">Sửa</a>
                                            @endif
                                            @if(Auth::user()->hasPermission('category-delete'))
                                            <button data-id="{{ $category->id }}" class="delete-category text-red-500 hover:text-red-700">Xóa</button>
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

        $("#search-category").on("input", function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                let keyword = $(this).val();

                $.ajax({
                    url: "{{ route('categories.search') }}",
                    type: "GET",
                    data: { keyword: keyword },
                    success: function (response) {
                        let rows = "";
                        response.categories.forEach(category => {
                            rows += `
                                <tr>
                                    <td class="py-3 px-4">${category.name}</td>
                                    <td class="py-3 px-4">${category.children_count}</td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="/categories/${category.id}" class="text-blue-500 hover:text-blue-700">Xem</a>
                                        @if(Auth::user()->hasPermission('category-edit'))
                                        <a href="/categories/${category.id}/edit" class="ml-2 text-green-500 hover:text-green-700">Sửa</a>
                                        @endif
                                        @if(Auth::user()->hasPermission('category-delete'))
                                        <button data-id="${category.id}" class="delete-category text-red-500 hover:text-red-700">Xóa</button>
                                        @endif
                                    </td>
                                </tr>
                            `;
                        });

                        $("#category-list").html(rows);
                        $("#pagination-links").html(response.pagination);
                    },
                    error: function () {
                        alert("Có lỗi xảy ra khi tìm kiếm.");
                    }
                });
            }, 500);
        });
    });
</script>

</x-app-layout> 