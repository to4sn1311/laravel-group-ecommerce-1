$(document).ready(function () {
    // Biến toàn cục
    let debounceTimer;
    const parentId = categoryData.parentId || window.location.pathname.split("/").pop();

    // Xử lý xóa danh mục
    $(document).on("click", ".delete-category", function () {
        let categoryId = $(this).data("id");
        if (!confirm("Bạn có chắc muốn xóa danh mục này?")) return;

        $.ajax({
            url: `/categories/${categoryId}`, 
            type: "DELETE",
            headers: { "X-CSRF-TOKEN": categoryData.csrfToken },
            success: function (response) {
                alert(response.message);
                location.reload();
            },
            error: function (xhr) {
                alert("Có lỗi xảy ra: " + xhr.responseJSON.message);
            }
        });
    });

    // Hàm lấy danh sách child categories
    function fetchChildCategories(url = `/categories/${parentId}/search-children`, keyword = "") {
        $.ajax({
            url: url,
            type: "GET",
            data: { keyword: keyword },
            success: function (response) {
                let rows = "";
                if (response.categories.length === 0) {
                    rows = `
                        <tr>
                            <td colspan="2" class="py-3 px-4 text-center text-gray-500 italic">
                                Không có bản ghi nào.
                            </td>
                        </tr>
                    `;
                } else {
                    response.categories.forEach(category => {
                        rows += `
                            <tr>
                                <td class="py-3 px-4">${category.name.slice(0, 30)}</td>
                                <td class="py-3 px-4 text-center">
                                    ${categoryData.permissions.canEdit ? 
                                        `<a href="/categories/${category.id}/edit" 
                                            class="inline-flex items-center px-3 py-1 ml-2 text-gray-900 bg-white border border-gray-300 rounded-lg shadow-md hover:bg-gray-100 transition">
                                            ✏️ Sửa
                                        </a>` : ''}
                                    ${categoryData.permissions.canDelete ? 
                                        `<button data-id="${category.id}"  
                                            class="inline-flex items-center px-3 py-1 ml-2 text-white bg-red-500 rounded-lg shadow-md hover:bg-red-600 transition delete-category">
                                            🗑️ Xóa
                                        </button>` : ''}
                                </td>
                            </tr>
                        `;
                    });
                }
    
                $("#child-category-list").html(rows);
                $("#pagination-links").html(response.pagination);
            },
            error: function () {
                alert("Có lỗi xảy ra khi tải dữ liệu.");
            }
        });
    }
    

    // Lắng nghe sự kiện tìm kiếm
    $("#search-child-category").on("input", function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            let keyword = $(this).val();
            fetchChildCategories(`/categories/${parentId}/search-children`, keyword);
        }, 500);
    });

    // Xử lý sự kiện click vào phân trang
    $(document).on("click", "#pagination-links a", function (e) {
        e.preventDefault();
        let url = $(this).attr("href");
        let keyword = $("#search-child-category").val();
        fetchChildCategories(url, keyword);
    });

    // Load danh sách ban đầu khi chưa tìm kiếm
    fetchChildCategories();
});