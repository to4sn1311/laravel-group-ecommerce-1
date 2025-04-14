<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chỉnh sửa người dùng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

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

                        <!-- Tên người dùng -->
                        <div>
                            <x-input-label for="name" :value="__('Tên')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Mật khẩu -->
                        <div>
                            <x-input-label for="password" :value="__('Mật khẩu')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Để trống nếu không muốn thay đổi mật khẩu</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Xác nhận mật khẩu -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Xác nhận mật khẩu')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Vai trò -->
                        <div>
                            <x-input-label :value="__('Vai trò')" />
                            <div class="mt-2 space-y-2">
                                @foreach($roles as $role)
                                <div class="flex items-center">
                                    @php
                                        $canEditRole = Auth::user()->hasPermission('role-edit');
                                        $isSuperAdmin = $role->name === 'Super Admin';
                                        $userHasRole = in_array($role->id, old('roles', $userRoles));
                                        $isCurrentUserRole = $user->id === Auth::id() && $userHasRole;
                                    @endphp

                                    @if($canEditRole || $role->name === 'User' || $isCurrentUserRole)
                                        <input id="role_{{ $role->id }}" type="checkbox" name="roles[]" value="{{ $role->id }}"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            {{ $userHasRole ? 'checked' : '' }}
                                            {{ $isCurrentUserRole && $role->name === 'User' ? 'disabled' : '' }}>
                                    @else
                                        <input id="role_{{ $role->id }}" type="checkbox" name="roles[]" value="{{ $role->id }}"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            {{ $userHasRole ? 'checked' : '' }} disabled>
                                    @endif

                                    <label for="role_{{ $role->id }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        {{ $role->name }}
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $role->description }}</span>

                                        @if(!$canEditRole && !($role->name === 'User') && !$isCurrentUserRole)
                                            <span class="text-xs text-red-500 dark:text-red-400">(Bạn không có quyền gán vai trò này)</span>
                                        @endif

                                        @if($isCurrentUserRole && $role->name === 'User')
                                            <span class="text-xs text-blue-500 dark:text-blue-400">(Vai trò hiện tại của bạn)</span>
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('users.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                                {{ __('Hủy') }}
                            </a>

                            @if(Auth::user()->hasPermission('user-edit') || Auth::id() === $user->id)
                            <x-primary-button>
                                {{ __('Cập nhật') }}
                            </x-primary-button>
                            @else
                            <x-primary-button disabled class="opacity-50 cursor-not-allowed">
                                {{ __('Cập nhật') }}
                            </x-primary-button>
                            <span class="text-xs text-red-500 ml-2">(Bạn không có quyền cập nhật)</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>