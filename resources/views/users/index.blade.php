<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Quản lý người dùng') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					@if(Auth::user()->hasPermission('user-create'))
					<div class="mb-6">
						<a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
							{{ __('Thêm người dùng mới') }}
						</a>
					</div>
					@endif

					<div class="mb-6">
						<form action="{{ route('users.index') }}" method="GET" class="flex items-center space-x-4">
							<div class="flex-1">
								<input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Tìm kiếm theo tên hoặc email') }}"
									class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-black-900 dark:text-gray-100 bg-white dark:bg-gray-700">
							</div>
							<div>
								<select name="per_page" onchange="this.form.submit()" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-black-900 dark:text-gray-100 bg-white dark:bg-gray-700">
									<option value="10" class="bg-white dark:bg-gray-700" {{ request('per_page') == 10 ? 'selected' : '' }}>10 {{ __('mục/trang') }}</option>
									<option value="25" class="bg-white dark:bg-gray-700" {{ request('per_page') == 25 ? 'selected' : '' }}>25 {{ __('mục/trang') }}</option>
									<option value="50" class="bg-white dark:bg-gray-700" {{ request('per_page') == 50 ? 'selected' : '' }}>50 {{ __('mục/trang') }}</option>
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
									<th class="py-3 px-4 text-left">{{ __('Email') }}</th>
									<th class="py-3 px-4 text-left">{{ __('Vai trò') }}</th>
									<th class="py-3 px-4 text-left">{{ __('Ngày tạo') }}</th>
									<th class="py-3 px-4 text-left">{{ __('Thao tác') }}</th>
								</tr>
							</thead>
							<tbody>
								@foreach($users as $user)
								<tr class="border-b border-gray-200 dark:border-gray-700">
									<td class="py-3 px-4">{{ $user->name }}</td>
									<td class="py-3 px-4">{{ $user->email }}</td>
									<td class="py-3 px-4">
										@foreach($user->roles as $role)
										<span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
											{{ $role->name }}
										</span>
										@endforeach
									</td>
									<td class="py-3 px-4">{{ $user->created_at->format('d/m/Y') }}</td>
									<td class="py-3 px-4">
										@if(Auth::user()->hasPermission('user-edit'))
										<a href="{{ route('users.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700 mr-2">
											{{ __('Sửa') }}
										</a>
										@endif

										@if(Auth::user()->hasPermission('user-delete'))
										<form method="POST" action="{{ route('users.destroy', $user->id) }}" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?');">
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
						{{ $users->appends(request()->query())->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>