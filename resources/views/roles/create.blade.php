<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Thêm vai trò mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('roles.store') }}" class="space-y-6">
                        @csrf

                        <!-- Thông báo lỗi -->
                        @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Có lỗi xảy ra!</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Tên vai trò -->
                        <div>
                            <x-input-label for="name" :value="__('Tên vai trò')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Mô tả -->
                        <div>
                            <x-input-label for="description" :value="__('Mô tả')" />
                            <textarea id="description" name="description" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Quyền -->
                        <div>
                            <x-input-label :value="__('Quyền')" />
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                                @foreach($permissions->groupBy(function($item) {
                                return explode('-', $item->name)[0];
                                }) as $group => $items)
                                <div class="border p-4 rounded dark:border-gray-700">
                                    <h3 class="font-semibold mb-2 capitalize">{{ $group }}</h3>
                                    @foreach($items as $permission)
                                    <div class="flex items-center mb-2">
                                        <input id="permission_{{ $permission->id }}" type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                        <label for="permission_{{ $permission->id }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                            {{ $permission->name }}
                                            @if($permission->description)
                                            <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $permission->description }}</span>
                                            @endif
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('roles.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                                {{ __('Hủy') }}
                            </a>
                            <x-primary-button>
                                {{ __('Thêm vai trò') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>