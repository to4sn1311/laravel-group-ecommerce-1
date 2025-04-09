<div id="showProductModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl" style="height: 70%;">
        <div class="modal-dialog px-4">
            <div class="modal-header items-center justify-items-center flex">
                <h2 class="modal-title text-white text-xl justify-center w-full h-full text-center pt-2" id="modal-title">Chi Tiết Sản Phẩm</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body mt-4">
                <div class="mb-4">
                    <p class="text-white"><strong>Tên:</strong> <span class="product-name text-gray-300"></span></p>
                </div>
                <div class="mb-4">
                    <p class="text-white"><strong>Giá:</strong> <span class="product-price text-gray-300"></span></p>
                </div>
                <div class="mb-4">
                    <p class="text-white"><strong>Mô tả:</strong> <span class="product-description text-gray-300"></span></p>
                </div>
            </div>
            <div class="modal-footer flex justify-between mt-4 mb-4">
                <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600" id="btn-close-show-product-modal" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>