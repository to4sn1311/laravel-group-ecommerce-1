<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Quản lý Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

              <!-- Thông báo lỗi -->
                <div id="error-messages"></div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">    
                    @if($categories->isNotEmpty())
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">{{ __('Thêm danh mục:') }}</h2>

                    <form id="category-form" method="POST" action="{{ route('categories.store') }}" class="space-y-6">
                        @csrf
                        <!-- Tên -->
                        <div>
                            <x-input-label for="name" :value="__('Tên')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        </div>

                        <!-- Trường Danh Mục Cấp 1 -->
                        <div class="mb-4">
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Danh mục cấp 1</label>
                            <select name="parent_id" id="parent_id" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="null">Root</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ Str::limit($category->name, 30) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('categories.index') }}" class="text-gray-500 hover:text-gray-700 font-semibold mr-4">
                                {{ __('Hủy') }}
                            </a>
                            <x-primary-button>
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                    </form>

                    @endif
                
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var categoryIndexRoute = "{{ route('categories.index') }}";
    </script>
    
    <!-- Custom JS -->
    <script src="{{ asset('assets/js/category/create.js') }}"></script>
</x-app-layout>
