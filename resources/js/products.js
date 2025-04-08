import axios from 'axios';

document.addEventListener('DOMContentLoaded', function () {
    loadProducts();

    document.getElementById('createProductBtn').addEventListener('click', function () {
        resetForm();
        document.getElementById('modal-title').innerText = 'Tạo sản phẩm';
        document.getElementById('product-form').dataset.action = 'create';
        showModal();
    });

    document.getElementById('product-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = e.target;
        const isCreate = form.dataset.action === 'create';

        const formData = new FormData(form);
        const url = isCreate ? '/products' : `/products/${formData.get('id')}`;
        const method = isCreate ? 'post' : 'put';

        axios({
            method: method,
            url: url,
            data: formData
        })
            .then(res => {
                if (res.data.success) {
                    hideModal();
                    loadProducts();
                    alert('Lưu sản phẩm thành công!');
                }
            })
            .catch(err => {
                if (err.response && err.response.status === 422) {
                    const errors = err.response.data.errors;
                    Object.keys(errors).forEach(key => {
                        const el = document.getElementById(`${key}_error`);
                        if (el) el.innerText = errors[key][0];
                    });
                } else {
                    alert('Lỗi khi lưu sản phẩm');
                }
            });
    });

    function loadProducts() {
        axios.get('/products')
            .then(res => {
                const products = res.data.products;
                const tbody = document.getElementById('productTableBody');
                tbody.innerHTML = '';
                products.forEach(product => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="px-4 py-2">${product.id}</td>
                        <td class="px-4 py-2">${product.name}</td>
                        <td class="px-4 py-2">${product.price}</td>
                        <td class="px-4 py-2 space-x-2">
                            <button onclick="editProduct(${product.id})" class="text-blue-400">Edit</button>
                            <button class="delete-btn text-red-400" data-id="${product.id}">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
                attachDeleteEvents();
            });
    }

    document.getElementById('btn-close-modal').addEventListener('click', function () {
        hideModal();
    });
});

function attachDeleteEvents() {
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            if (confirm('Bạn có chắc chắn muốn xóa?')) {
                axios.delete(`/products/${id}`)
                    .then(res => {
                        if (res.data.success) {
                            alert('Đã xóa thành công');
                            loadProducts(); // reload lại danh sách
                        } else {
                            alert('Xóa thất bại: ' + res.data.message);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Lỗi khi xóa');
                    });
            }
        });
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
}