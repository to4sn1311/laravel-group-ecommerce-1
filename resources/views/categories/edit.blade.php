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
                    <form method="POST" action="{{ route('categories.update', $category->id) }}">
                        @csrf
                        @method('PUT')
                    
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
                                        {{ $category->parent_id == $parentCategory->id ? 'selected' : '' }}>
                                        {{ $parentCategory->name }}
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
</x-app-layout>
