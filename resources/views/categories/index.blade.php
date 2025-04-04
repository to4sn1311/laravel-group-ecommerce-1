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
                                <!-- Danh mục cha -->
                                <tr class="border-b border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                    <td class="py-3 px-4 text-gray-900 dark:text-gray-100 font-bold">
                                        <button onclick="toggleChildren({{ $category->id }})" class="text-blue-500">
                                            {{ $category->name }}
                                        </button>
                                    </td>
                                    <td class="py-3 px-4 flex justify-center space-x-2">
                                        <a href="{{ route('categories.edit', $category->id) }}" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                            {{ __('Sửa') }}
                                        </a>
                                        <form method="POST" action="{{ route('categories.destroy', $category->id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700 transition">
                                                {{ __('Xóa') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Danh mục con (ẩn mặc định) -->
                                @if(isset($children[$category->id]) && $children[$category->id]->count() > 0)
                                    @foreach($children[$category->id] as $child)
                                    <tr class="hidden child-row-{{ $category->id }} border-b border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800">
                                        <td class="py-3 px-8 text-gray-700 dark:text-gray-300 flex items-center">
                                            ├── {{ $child->name }}
                                        </td>
                                        <td class="py-3 px-4 flex justify-center space-x-2">
                                            <a href="{{ route('categories.edit', $child->id) }}" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                                {{ __('Sửa') }}
                                            </a>
                                            <form method="POST" action="{{ route('categories.destroy', $child->id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700 transition">
                                                    {{ __('Xóa') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
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

    <script>
        function toggleChildren(parentId) {
            let childRows = document.querySelectorAll('.child-row-' + parentId);
            childRows.forEach(row => row.classList.toggle('hidden'));
        }
    </script>
</x-app-layout>
