<div id="productModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl" style="height: 70%;">
        <div class="modal-dialog px-4">
            <form id="product-form" class="modal-content" data-action="create">
                @csrf

                <div class="modal-header items-center justify-items-center flex">
                    <h2 class="modal-title text-white text-xl justify-center w-full h-full text-center pt-2" id="modal-title">Create Product</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body space-y-3">
                    <input type="hidden" name="id" id="product-id">
                    <!-- Tên sản phẩm -->
                    <div class="w-full mb-4">
                        <x-input-label for="name" :value="__('Tên:')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" id="name_error" />
                    </div>

                    <!-- Giá -->
                    <div class="mb-4">
                        <x-input-label for="price" :value="__('Giá:')" />
                        <x-text-input id="price" class="block mt-1 w-full no-spinner" type="number" name="price" required />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" id="price_error" />
                    </div>

                    <!-- Chi tiết -->
                    <div class="mb-4">
                        <x-input-label for="description" :value="__('Chi tiết:')" />
                        <x-textarea id="description" class="block mt-1 w-full" rows="5" name="description" required></x-textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" id="description_error" />
                    </div>

                    <!-- Danh mục sản phẩm -->

                    <div class="mb-4">
                        <x-select-multi
                            id="categories"
                            name="categories"
                            :label="__('Danh mục sản phẩm:')"
                            :options="$categories"
                            :selected="old('categories', $selectedCategories ?? [])" />

                    </div>

                </div>
                <div class="modal-footer flex justify-between mt-4 mb-4">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600" id="btn-close-modal" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>