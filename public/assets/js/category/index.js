$(document).ready(function () {
    let debounceTimer;

    // Xử lý xóa danh mục
    $(document).on("click", ".delete-category", function () {
        let categoryId = $(this).data("id");
        if (!confirm("Bạn có chắc muốn xóa danh mục này?")) return;

        $.ajax({
            url: `/categories/${categoryId}`, 
            type: "DELETE",
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                alert(response.message);
                location.reload();
            },
            error: function (xhr) {
                alert("Có lỗi xảy ra: " + xhr.responseJSON.message);
            }
        });
    });

    // Hàm tải danh sách danh mục
    function fetchCategories(url, keyword = "") {
        $.ajax({
            url: url,
            type: "GET",
            data: { keyword: keyword },
            success: function (response) {
                let rows = "";
    
                if (response.categories.length === 0) {
                    rows = `
                        <tr>
                            <td colspan="3" class="py-4 px-4 text-center text-gray-500">
                                Không có bản ghi nào.
                            </td>
                        </tr>
                    `;
                } else {
                    response.categories.forEach(category => {
                        let viewBtn = window.hasListPermission ? 
                            `<a href="/categories/${category.id}" 
                                class="inline-flex items-center px-3 py-1 text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 transition">
                                📙 Xem
                            </a>` : '';
                        
                        let editBtn = window.hasEditPermission ? 
                            `<a href="/categories/${category.id}/edit" 
                                class="inline-flex items-center px-3 py-1 ml-2 text-gray-900 bg-white border border-gray-300 rounded-lg shadow-md hover:bg-gray-100 transition">
                                ✏️ Sửa
                            </a>` : '';
                        
                        let deleteBtn = window.hasDeletePermission ? 
                            `<button data-id="${category.id}" 
                                    class="inline-flex items-center px-3 py-1 ml-2 text-white bg-red-500 rounded-lg shadow-md hover:bg-red-600 transition delete-category">
                                🗑️ Xóa
                            </button>` : '';
    
                        rows += `
                            <tr>
                                <td class="py-3 px-4">${category.name.slice(0, 30)}</td>
                                <td class="py-3 px-4">${category.children_count}</td>
                                <td class="py-3 px-4 text-center">
                                    ${viewBtn}
                                    ${editBtn}
                                    ${deleteBtn}
                                </td>
                            </tr>
                        `;
                    });
                }
    
                $("#category-list").html(rows);
                $("#pagination-links").html(response.pagination || "");
            },
            error: function () {
                alert("Có lỗi xảy ra khi tải dữ liệu.");
            }
        });
    }
    

    // Lắng nghe input tìm kiếm
    $("#search-category").on("input", function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            let keyword = $(this).val();
            fetchCategories(window.categoriesSearchUrl, keyword);
        }, 500);
    });

    // Xử lý sự kiện click trên phân trang
    $(document).on("click", "#pagination-links a", function (e) {
        e.preventDefault();
        let url = $(this).attr("href");
        let keyword = $("#search-category").val();
        fetchCategories(url, keyword);
    });

    // Khởi tạo: tải danh sách ban đầu
    if ($("#category-list").length) {
        fetchCategories(window.categoriesSearchUrl);
    }
});