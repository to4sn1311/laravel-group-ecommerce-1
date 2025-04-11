import { showModal } from './modal'
import { loadProducts } from './productTable'
import axios from 'axios';
import debounce from 'lodash.debounce';

export function showProduct(id) {
    axios.get(`/products/${id}`)
        .then(response => {
            const product = response.data.data;
            $('#showProductModal .product-name').text(product.name);
            $('#showProductModal .product-price').text(product.price);
            $('#showProductModal .product-description').text(product.description);
            showModal('showProductModal');
        })
        .catch(error => {
            console.error(error);
            alert('Không thể tải thông tin sản phẩm!');
        });
}

export function editProduct(id) {
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

            // Đặt tiêu đề modal
            document.getElementById('modal-title').innerText = 'Chỉnh sửa sản phẩm';
            // Gán dữ liệu vào form
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

            showModal('productModal');
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


export function deleteProduct(id) {
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

                loadProducts() // tải lại danh sách
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
