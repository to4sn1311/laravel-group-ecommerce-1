<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chỉnh sửa category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form id="category-update-form" method="POST" action="{{ route('categories.update', $category->id) }}">
                        @csrf
                        @method('PUT')
                        <div id="error-messages"></div> <!-- Vị trí để hiển thị lỗi -->

                        <div class="mb-4">
                            <label for="name" class="block text-gray-900 dark:text-gray-100 text-sm font-bold mb-2">Tên danh mục:</label>
                            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-700">
                        </div>
                    
                        <div class="mb-4">
                            <label for="parent_id" class="block text-gray-900 dark:text-gray-100 text-sm font-bold mb-2">Danh mục cha:</label>
                            <select name="parent_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-700">
                                <option value="null">Không có danh mục cha</option>
                                @foreach($categories as $parentCategory)
                                    <option value="{{ $parentCategory->id }}" 
                                        {{ $category->parent_id == $parentCategory->id ? 'selected' : '' }}>{{ $parentCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#category-update-form').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                var actionUrl = $(this).attr('action');

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Nếu thành công, chuyển hướng về danh sách category
                        window.location.href = '{{ route('categories.index') }}';
                    },
                    error: function(xhr) {
                        // Hiển thị lỗi nếu có
                        $('#error-messages').html('');
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                $('#error-messages').append('<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert"><strong class="font-bold">Có lỗi xảy ra!</strong><ul><li>' + messages[0] + '</li></ul></div>');
                            });
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout>
