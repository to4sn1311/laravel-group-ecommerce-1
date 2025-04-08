<x-app-layout>
    <!-- Modal Header -->
    <div class="flex justify-between items-center mb-4">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Sửa sản phẩm') }}
            </h2>
        </x-slot>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Modal Body -->
                    <form id="productForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="productId" name="id" value="{{ $product->id }}">

                        <!-- Tên sản phẩm -->
                        <div class="w-full mb-4">
                            <x-input-label for="name" :value="__('Tên:')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                value="{{ $product->name }}" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" id="name_error" />
                        </div>

                        <!-- Giá -->
                        <div class="mb-4">
                            <x-input-label for="price" :value="__('Giá:')" />
                            <x-text-input id="price" class="block mt-1 w-full no-spinner" type="number"
                                name="price" value="{{ $product->price }}" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" id="price_error" />
                        </div>

                        <!-- Chi tiết -->
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Chi tiết:')" />
                            <x-textarea id="description" class="block mt-1 w-full" rows="3"
                                name="description" required value="{{$product->description}}" />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" id="description_error" />
                        </div>

                        <!-- Danh mục sản phẩm -->
                        <div class="mb-4">
                            <x-input-label for="category_id" :value="__('Danh mục sản phẩm:')" />
                            <select id="category_id" name="category_id" class="form-control w-full dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" id="category_id_error" />
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end">
                            <button type="button" id="closeModalBtn2" class="px-4 py-2 mr-2 text-gray-500 border border-gray-300 rounded-lg dark:text-gray-300 dark:border-gray-600">Hủy</button>
                            <button type="submit" id="submitProductBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    /* Ẩn spinner trong trình duyệt Webkit (Chrome, Safari) */
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Ẩn spinner trong Firefox */
    .no-spinner[type="number"] {
        -moz-appearance: textfield;
    }
</style>