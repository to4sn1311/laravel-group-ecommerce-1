<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Thông tin người dùng') }}
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
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Tên') }}</p>
                                <p>{{ $user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Email') }}</p>
                                <p>{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Ngày tạo') }}</p>
                                <p>{{ $user->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Cập nhật lần cuối') }}</p>
                                <p>{{ $user->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Vai trò -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Vai trò') }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->roles as $role)
                            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                {{ $role->name }}
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quyền -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Quyền') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            @php
                                $permissions = collect();
                                foreach($user->roles as $role) {
                                    $permissions = $permissions->merge($role->permissions);
                                }
                                $permissions = $permissions->unique('id');
                            @endphp

                            @foreach($permissions as $permission)
                            <div class="bg-gray-100 text-gray-800 px-3 py-1 rounded dark:bg-gray-700 dark:text-gray-300">
                                {{ $permission->name }}
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Thao tác -->
                    <div class="flex justify-end">
                        <a href="{{ route('users.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                            {{ __('Quay lại') }}
                        </a>
                        @if(Auth::user()->hasPermission('user-edit'))
                        <a href="{{ route('users.edit', $user->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                            {{ __('Chỉnh sửa') }}
                        </a>
                        @endif
                        @if(Auth::user()->hasPermission('user-delete') && Auth::id() != $user->id)
                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Xóa') }}
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 