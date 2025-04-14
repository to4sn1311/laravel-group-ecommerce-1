import { showProduct, editProduct, deleteProduct } from "./productApi";

export function loadProducts(url = '/products') {
    const keyword = document.getElementById('search-name').value;
    const priceRange = document.getElementById('price-range').value;
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        data: {
            keyword: keyword,
            price_range: priceRange
        },
        success: function (response) {
            if (response.products) {
                renderProductTable(response.products);
                renderPagination(response.pagination);
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

export function renderProductTable(products) {
    const tbody = document.getElementById('productTableBody');
    tbody.innerHTML = '';

    if (!products || products.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-gray-300">Không có sản phẩm nào.</td></tr>`;
        return;
    }

    products.forEach(product => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="px-4 py-2">${product.id}</td>
            <td class="px-4 py-2">${product.name}</td>
            <td class="px-4 py-2">${new Intl.NumberFormat().format(product.price)} đ</td>
            <td class="px-4 py-2 space-x-2">
                <button class="px-3 py-1 text-sm bg-yellow-600 text-white rounded hover:bg-yellow-700 transition show-btn" data-id="${product.id}">Show</button>
                <button class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition edit-btn" data-id="${product.id}">Edit</button>
                <button class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700 transition delete-btn" data-id="${product.id}">Delete</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

export function attachDeleteEvents() {
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
                this.disabled = true;
                this.textContent = 'Đang xóa...';
                deleteProduct(id);
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

export function detachDeleteEvents() {
    document.querySelectorAll('.edit-btn').forEach(btn => btn.replaceWith(btn.cloneNode(true)));
    document.querySelectorAll('.delete-btn').forEach(btn => btn.replaceWith(btn.cloneNode(true)));
    document.querySelectorAll('.show-btn').forEach(btn => btn.replaceWith(btn.cloneNode(true)));
}

export function renderPagination(pagination) {
    const paginationContainer = document.getElementById('paginationLinks');
    paginationContainer.innerHTML = pagination;

    paginationContainer.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.href;
            loadProducts(url);
        });
    });
}
