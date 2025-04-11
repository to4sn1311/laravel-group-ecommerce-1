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
                // Vô hiệu hóa nút xóa để tránh click nhiều lần
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
