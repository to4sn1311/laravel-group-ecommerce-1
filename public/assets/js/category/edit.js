const HTTP_UNPROCESSABLE_ENTITY = 422;

$(document).ready(function() {
    $('#category-update-form').submit(function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var actionUrl = $(this).attr('action');

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            success: function(response) {
                alert(response.message);
                // Nếu thành công, chuyển hướng về danh sách category
                window.location.href = categoriesIndexRoute;
            },
            error: function(xhr) {
                // Hiển thị lỗi nếu có
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
    });
});