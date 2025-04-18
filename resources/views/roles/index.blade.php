<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quản lý vai trò') }}
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

                    @if(Auth::user()->hasPermission('role-create'))
                    <div class="mb-6">
                        <a href="{{ route('roles.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Thêm vai trò mới') }}
                        </a>
                    </div>
                    @endif

                    <div class="mb-6">
                        <form action="{{ route('roles.index') }}" method="GET" class="flex items-center space-x-4 w-full">
                            <div class="flex-1">
                                <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Tìm kiếm theo tên hoặc mô tả') }}" 
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-black-900 dark:text-gray-100 bg-white dark:bg-gray-700">
                            </div>
                            <div>
                                <select name="per_page" onchange="this.form.submit()" class=" pr-8 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-black-900 dark:text-gray-100 bg-white dark:bg-gray-700">
                                    <option value="10" class="bg-white dark:bg-gray-700" {{ request('per_page') == 10 ? 'selected' : '' }}>10{{ __('/trang') }}</option>
                                    <option value="25" class="bg-white dark:bg-gray-700" {{ request('per_page') == 25 ? 'selected' : '' }}>25{{ __('/trang') }}</option>
                                    <option value="50" class="bg-white dark:bg-gray-700" {{ request('per_page') == 50 ? 'selected' : '' }}>50{{ __('/trang') }}</option>
                                </select>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                {{ __('Tìm kiếm') }}
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-700">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-gray-100">
                                    <th class="py-3 px-4 text-left">{{ __('Tên') }}</th>
                                    <th class="py-3 px-4 text-left">{{ __('Mô tả') }}</th>
                                    <th class="py-3 px-4 text-left">{{ __('Quyền') }}</th>
                                    <th class="py-3 px-4 text-left">{{ __('Người dùng') }}</th>
                                    <th class="py-3 px-4 text-left">{{ __('Thao tác') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-3 px-4">{{ $role->name }}</td>
                                    <td class="py-3 px-4">{{ $role->description }}</td>
                                    <td class="py-3 px-4">
                                        <span class="text-xs">{{ $role->permissions->count() }} quyền</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-xs">{{ $role->users->count() }} người dùng</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <a href="{{ route('roles.show', $role->id) }}" class="text-blue-500 hover:text-blue-700 mr-2">
                                            {{ __('Xem') }}
                                        </a>
                                        
                                        @if(Auth::user()->hasPermission('role-edit'))
                                        <a href="{{ route('roles.edit', $role->id) }}" class="text-blue-500 hover:text-blue-700 mr-2">
                                            {{ __('Sửa') }}
                                        </a>
                                        @endif
                                        
                                        @if(Auth::user()->hasPermission('role-delete'))
                                        <form method="POST" action="{{ route('roles.destroy', $role->id) }}" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa vai trò này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
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

                    <div class="mt-4">
                        {{ $roles->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 