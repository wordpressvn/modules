jQuery(document).ready(function($) {
    $('.delete-class-btn').on('click', function(e) {
        e.preventDefault();

        var fileName = $(this).data('file-name');
        var confirmation = confirm(modules.deletes);

        if (confirmation) {
            $.ajax({
                url: modules.ajax_url,
                method: 'POST',
                data: {
                    action: 'modules_classes',
                    modules_nonce: modules.modules_nonce,
                    file_name: fileName
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data);
                        location.reload();
                    } else {
                        alert(response.data);
                    }
                },
                error: function() {
                    alert(modules.errors);
                }
            });
        }
    });
});
