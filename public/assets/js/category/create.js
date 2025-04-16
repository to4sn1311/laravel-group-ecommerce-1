const HTTP_UNPROCESSABLE_ENTITY = 422;

$(document).ready(function() {
    $('#category-form').submit(function(e) {
        e.preventDefault();
        submitCategoryForm($(this));
    });

    $('#category-form1').submit(function(e) {
        e.preventDefault();
        submitCategoryForm($(this));
    });

    function submitCategoryForm(form) {
        var formData = form.serialize();

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                alert(response.message);
                // Nếu thành công, chuyển hướng về danh sách category
                window.location.href = categoryIndexRoute;
            },
            error: function(xhr) {
                // Xử lý lỗi (hiển thị lỗi trả về từ server)
                $('#error-messages').html('');
                if (xhr.status === HTTP_UNPROCESSABLE_ENTITY) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('#error-messages').append('<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert"><strong class="font-bold">Có lỗi xảy ra!</strong><ul><li>' + messages[0] + '</li></ul></div>');
                    });
                } else {
                    alert("Có lỗi xảy ra: " + xhr.responseJSON.message);
                }
            }
        });
    }
});