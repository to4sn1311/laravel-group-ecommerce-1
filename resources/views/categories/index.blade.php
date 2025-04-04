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
                    @if(Auth::user()->hasPermission('category-create'))
                    <div class="mb-6">
                        <a href="{{ route('categories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Thêm category mới') }}
                        </a>
                    </div>
                    @endif
                    <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-md">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">{{ __('Danh sách danh mục') }}</h2>
                    
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse rounded-lg shadow-sm bg-white dark:bg-gray-700">
                                <thead>
                                    <tr class="bg-blue-500 text-white">
                                        <th class="py-3 px-4 text-left">{{ __('Tên') }}</th>
                                        <th class="py-3 px-4 text-left">{{ __('Count_child') }}</th>
                                        <th class="py-3 px-4 text-center">{{ __('Hành động') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    <tr class="border-b border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                        <td class="py-3 px-4 text-gray-900 dark:text-gray-100">{{ $category->name }}</td>
                                        <td class="py-3 px-4 text-gray-900 dark:text-gray-100">{{ $category->children_count }}</td>
                                        <td class="py-3 px-4 flex justify-center space-x-2">
                                            <a href="{{ route('categories.show', $category->id) }}" class="text-blue-500 hover:text-blue-700 mr-2">
                                                {{ __('Xem') }}
                                            </a>
                                            @if(Auth::user()->hasPermission('category-edit'))
                                            <a href="{{ route('categories.edit', $category->id) }}" 
                                                class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                                {{ __('Sửa') }}
                                            </a>
                                            @endif
                                            
                                            @if(Auth::user()->hasPermission('category-delete'))
                                            <form method="POST" action="{{ route('categories.destroy', $category->id) }}" 
                                                onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700 transition">
                                                    {{ __('Xóa') }}
                                                </button>
                                            </form>
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
</x-app-layout> 