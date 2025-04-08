<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chi tiết vai trò') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Thông tin cơ bản -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Thông tin cơ bản') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Tên vai trò') }}</p>
                                <p>{{ $category->name }}</p>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="mb-8">
                        <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">{{ __('Danh sách danh mục') }}</h2>
                        
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse rounded-lg shadow-sm bg-white dark:bg-gray-700">
                                    <thead>
                                        <tr class="bg-blue-500 text-white">
                                            <th class="py-3 px-4 text-left">{{ __('Tên') }}</th>
                                            <th class="py-3 px-4 text-center">{{ __('Hành động') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($categories as $category)
                                        <tr class="border-b border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                            <td class="py-3 px-4 text-gray-900 dark:text-gray-100">{{ $category->name }}</td>
                                            <td class="py-3 px-4 flex justify-center space-x-2">
                                                @if(Auth::user()->hasPermission('category-edit'))
                                                <a href="{{ route('categories.edit', $category->id) }}" 
                                                    class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                                    {{ __('Sửa') }}
                                                </a>
                                                @endif
                                                
                                                @if(Auth::user()->hasPermission('category-delete'))
                                                <button 
                                                    data-id="{{ $category->id }}" 
                                                    class="delete-category px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700 transition">
                                                    {{ __('Xóa') }}
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        
                            <div class="mt-6">
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
            $(".delete-category").click(function () {
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
</x-app-layout> 