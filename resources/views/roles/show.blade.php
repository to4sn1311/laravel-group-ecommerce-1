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
                                <p>{{ $role->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Mô tả') }}</p>
                                <p>{{ $role->description ?: 'Không có mô tả' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Số người dùng có vai trò này') }}</p>
                                <p>{{ $role->users->count() }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Số quyền') }}</p>
                                <p>{{ $role->permissions->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quyền -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Quyền') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($role->permissions->groupBy(function($item) {
                                return explode('-', $item->name)[0];
                            }) as $group => $permissions)
                            <div class="border p-4 rounded dark:border-gray-700">
                                <h4 class="font-semibold mb-2 capitalize">{{ $group }}</h4>
                                <ul class="list-disc list-inside">
                                    @foreach($permissions as $permission)
                                    <li class="text-sm">
                                        {{ $permission->name }}
                                        @if($permission->description)
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $permission->description }}</span>
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Người dùng có vai trò này -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Người dùng có vai trò này') }}</h3>
                        @if($role->users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-700">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-gray-100">
                                        <th class="py-2 px-4 text-left">{{ __('Tên') }}</th>
                                        <th class="py-2 px-4 text-left">{{ __('Email') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($role->users->take(10) as $user)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4">{{ $user->name }}</td>
                                        <td class="py-2 px-4">{{ $user->email }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($role->users->count() > 10)
                            <div class="mt-2 text-sm text-gray-500">
                                {{ __('Hiển thị 10/' . $role->users->count() . ' người dùng') }}
                            </div>
                            @endif
                        </div>
                        @else
                        <p>{{ __('Không có người dùng nào có vai trò này') }}</p>
                        @endif
                    </div>

                    <!-- Thao tác -->
                    <div class="flex justify-end">
                        <a href="{{ route('roles.index') }}" class="bg-gray-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                            {{ __('Quay lại') }}
                        </a>
                        @if(Auth::user()->hasPermission('role-edit'))
                        <a href="{{ route('roles.edit', $role->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                            {{ __('Chỉnh sửa') }}
                        </a>
                        @endif
                        @if(Auth::user()->hasPermission('role-delete') && $role->users->count() == 0)
                        <form method="POST" action="{{ route('roles.destroy', $role->id) }}" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa vai trò này?');">
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