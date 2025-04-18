import axios from 'axios';
import './modules/csrf';
import { handleImagePreview } from './modules/previewImage';
import { resetForm } from './modules/productForm';
import { showModal, hideModal } from './modules/modal';
import { loadProducts } from './modules/productTable';
import { attachPaginationEvents } from './modules/pagination';

document.addEventListener('DOMContentLoaded', function () {
    $('.select2').select2({ placeholder: 'Chọn danh mục', width: '100%', allowClear: true });
    handleImagePreview('image', 'preview-img', 'image-placeholder');
    let debounceTimeout;
    document.getElementById('search-name').addEventListener('input', function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(loadProducts, 300);
    });
    document.getElementById('price-range').addEventListener('change', function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(loadProducts, 300);
    });
    loadProducts('/products');
    attachPaginationEvents();

    document.getElementById('createProductBtn').addEventListener('click', function () {
        resetForm();
        document.getElementById('modal-title').innerText = 'Tạo sản phẩm';
        document.getElementById('product-form').dataset.action = 'create';
        showModal('productModal');
    });

    document.getElementById('image-preview').addEventListener('click', function () {
        document.getElementById('image').click();
    });

    document.getElementById('product-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = e.target;
        const isCreate = form.dataset.action === 'create';
        const method = 'POST';
        const formData = new FormData(form);
        const productId = formData.get('id');
        const url = isCreate ? '/products' : `/products/${productId}`;
        if (!isCreate) {
            formData.append('_method', 'PUT');
        }

        axios({
            method: method,
            url: url,
            data: formData,
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(res => {
                if (res.data.success) {
                    hideModal('productModal');
                    resetForm();
                    loadProducts();
                    const successMsg = document.createElement('div');
                    successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg';
                    successMsg.textContent = isCreate ? 'Đã tạo sản phẩm thành công' : 'Đã cập nhật sản phẩm thành công';
                    document.body.appendChild(successMsg);

                    setTimeout(() => successMsg.remove(), 3000);
                }
            })
            .catch(err => {
                console.error('Lỗi khi lưu sản phẩm:', err);
            })
    });

    document.getElementById('btn-close-modal').addEventListener('click', function () {
        hideModal('productModal');
    });


});


/*
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: <meta name="csrf-token" content="...">');
}
function loadProducts() {
    $.ajax({
        url: '/products',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            // Check if products property exists in the response
            if (response.products) {
                const products = response.products;
                const tbody = document.getElementById('productTableBody');

                if (!products || typeof products !== 'object') {
                    console.error('Invalid products data:', products);
                    return;
                }

                tbody.innerHTML = '';

                for (let i = 0; i < products.length; i++) {
                    const product = products[i];
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="px-4 py-2">${product.id}</td>
                        <td class="px-4 py-2">${product.name}</td>
                        <td class="px-4 py-2">${product.price}</td>
                        <td class="px-4 py-2 space-x-2">
                            <button class="px-3 py-1 text-sm bg-yellow-600 text-white rounded hover:bg-yellow-700 transition show-btn" data-id="${product.id}">Show</button>
                            <button class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition edit-btn" data-id="${product.id}">Edit</button>
                            <button class="px-3 py-1 delete-btn text-sm bg-red-600 text-white rounded hover:bg-red-700 transition" data-id="${product.id}">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                }

                attachDeleteEvents();
            } else if (response.success === false) {
                console.error('Error in API response:', response.message);
            } else {
                console.error('Unexpected response format:', response);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error loading products:', error);
            console.error('Status:', status);
            console.error('Response:', xhr.responseText);
        }
    });
}

function hideShowProductModal() {
    const modal = document.getElementById('showProductModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function showProductDetailModal() {
    const modal = document.getElementById('showProductModal');
    if (modal) {
        modal.classList.remove('hidden');
    } else {
        console.error('Không tìm thấy modal showProductModal!');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    $('.select2').select2({ placeholder: 'Chọn danh mục', width: '100%', allowClear: true });
    loadProducts();

    document.getElementById('createProductBtn').addEventListener('click', function () {
        resetForm();
        document.getElementById('modal-title').innerText = 'Tạo sản phẩm';
        document.getElementById('product-form').dataset.action = 'create';
        showModal();
    });

    document.getElementById('image-preview').addEventListener('click', function () {
        document.getElementById('image').click();
    });

    document.getElementById('image').addEventListener('change', function () {
        const file = this.files[0];
        const preview = document.getElementById('preview-img');
        const placeholder = document.getElementById('image-placeholder');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    });

    document.getElementById('product-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = e.target;
        const isCreate = form.dataset.action === 'create';
        const method = 'POST';
        const formData = new FormData(form);
        console.log('Dữ liệu form trước gửi:', [...formData.entries()]);
        const productId = formData.get('id');
        const url = isCreate ? '/products' : `/products/${productId}`;
        if (!isCreate) {
            formData.append('_method', 'PUT');
        }

        axios({
            method: 'POST',
            url: url,
            data: formData,
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(res => {
                if (res.data.success) {
                    hideModal();
                    resetForm();
                    loadProducts();
                    const successMsg = document.createElement('div');
                    successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg';
                    successMsg.textContent = isCreate ? 'Đã tạo sản phẩm thành công' : 'Đã cập nhật sản phẩm thành công';
                    document.body.appendChild(successMsg);

                    setTimeout(() => successMsg.remove(), 3000);
                }
            })
            .catch(err => {
                console.error('Lỗi khi lưu sản phẩm:', err);
            })
    });

    document.getElementById('btn-close-modal').addEventListener('click', function () {
        hideModal();
    });

    document.getElementById('btn-close-show-product-modal').addEventListener('click', function () {
        hideShowProductModal();
    });

});

function attachDeleteEvents() {
    detachDeleteEvents();
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            editProduct(id);
        });
    });
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const productName = this.closest('tr').querySelector('td:nth-child(2)').textContent;

            if (confirm(`Bạn có chắc chắn muốn xóa sản phẩm "${productName}"?`)) {
                // Vô hiệu hóa nút xóa để tránh click nhiều lần
                this.disabled = true;
                this.textContent = 'Đang xóa...';

                axios.delete(`/products/${id}`)
                    .then(res => {
                        if (res.data.success) {
                            // Hiển thị thông báo thành công
                            const successMessage = document.createElement('div');
                            successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg';
                            successMessage.textContent = 'Đã xóa sản phẩm thành công';
                            document.body.appendChild(successMessage);

                            // Xóa thông báo thành công sau 3 giây
                            setTimeout(() => {
                                successMessage.remove();
                            }, 3000);

                            loadProducts(); // tải lại danh sách
                        } else {
                            throw new Error(res.data.message || 'Không thể xóa sản phẩm');
                        }
                    })
                    .catch(err => {
                        console.error('Lỗi xóa:', err);

                        // Hiển thị thông báo lỗi
                        const errorMessage = document.createElement('div');
                        errorMessage.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg';
                        errorMessage.textContent = err.response?.data?.message || 'Lỗi khi xóa sản phẩm. Vui lòng thử lại.';
                        document.body.appendChild(errorMessage);

                        // Xóa thông báo lỗi sau 3 giây
                        setTimeout(() => {
                            errorMessage.remove();
                        }, 3000);

                        // Bật lại nút xóa
                        this.disabled = false;
                        this.textContent = 'Xóa';
                    });
            }
        });
    });
    document.querySelectorAll('.show-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            showProduct(id);
        })
    });
}

function showProduct(id) {
    axios.get(`/products/${id}`)
        .then(response => {
            const product = response.data.data;
            $('#showProductModal .product-name').text(product.name);
            $('#showProductModal .product-price').text(product.price);
            $('#showProductModal .product-description').text(product.description);
            showProductDetailModal();
        })
        .catch(error => {
            console.error(error);
            alert('Không thể tải thông tin sản phẩm!');
        });
}

function editProduct(id) {
    axios.get(`/products/${id}/edit`)
        .then(res => {
            if (!res.data || !res.data.success) {
                throw new Error('Phản hồi không hợp lệ từ máy chủ');
            }
            // Thử lấy dữ liệu sản phẩm từ nhiều vị trí có thể có trong phản hồi
            const product = res.data.product || res.data.data;

            if (!product) {
                throw new Error('Không tìm thấy dữ liệu sản phẩm trong phản hồi');
            }
            console.log(product);

            document.getElementById('modal-title').innerText = 'Chỉnh sửa sản phẩm';
            document.querySelector('#product-form [name="id"]').value = product.id;
            document.querySelector('#product-form [name="name"]').value = product.name;
            document.querySelector('#product-form [name="price"]').value = product.price;
            document.querySelector('#product-form [name="description"]').value = product.description;

            if (product.categories) {
                const categoryIds = product.categories.map(c => c.id);
                $('#product-form [name="categories[]"]').val(categoryIds).trigger('change');
            }

            const previewImg = document.getElementById('preview-img');
            const placeholder = document.getElementById('image-placeholder');

            if (product.image) {
                previewImg.src = product.image; // đường dẫn URL ảnh từ backend
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                previewImg.src = '#';
                previewImg.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            document.getElementById('product-form').dataset.action = 'edit';

            showModal();
        })
        .catch(err => {
            console.error('Lỗi khi tải sản phẩm:', err);

            // Hiển thị thông báo lỗi cho người dùng
            const errorMessage = document.createElement('div');
            errorMessage.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg';
            errorMessage.textContent = 'Không thể tải dữ liệu sản phẩm. Vui lòng thử lại.';
            document.body.appendChild(errorMessage);

            // Xóa thông báo lỗi sau 3 giây
            setTimeout(() => {
                errorMessage.remove();
            }, 3000);
        });
}

function showModal() {
    document.getElementById('productModal').classList.remove('hidden');
}

function hideModal() {
    document.getElementById('productModal').classList.add('hidden');
}

function resetForm() {
    document.getElementById('product-form').reset();
    document.getElementById('product-id').value = '';
    document.querySelectorAll('.error-text').forEach(el => el.innerText = '');
    const select = $('#categories');
    select.val([]).trigger('change'); // reset bằng Select2
    select.find('option:selected').prop('selected', false);

    const previewImg = document.getElementById('preview-img');
    const placeholder = document.getElementById('image-placeholder');
    const imageInput = document.getElementById('image');

    previewImg.src = '#';
    previewImg.classList.add('hidden');
    placeholder.classList.remove('hidden');
    imageInput.value = '';
}

function detachDeleteEvents() {
    document.querySelectorAll('.edit-btn').forEach(btn => btn.replaceWith(btn.cloneNode(true)));
    document.querySelectorAll('.delete-btn').forEach(btn => btn.replaceWith(btn.cloneNode(true)));
    document.querySelectorAll('.show-btn').forEach(btn => btn.replaceWith(btn.cloneNode(true)));
}


*/