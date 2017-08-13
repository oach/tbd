var already_loaded = [];
$(function() {
    $('#accordion').accordion({
        heightStyle: 'content',
        active: false,
        collapsible: true,
        beforeActivate: function(event, ui) {
            if (already_loaded[$(ui.newHeader).data('category-id')] != 1) {
                $.ajax({
                    url: '/user/establishmentList',
                    async: true,
                    type: 'post',
                    cache: false,
                    dataType: 'json',
                    data: {category_id: $(ui.newHeader).data('category-id'), user_id: $(ui.newHeader).data('user-id')},
                    beforeSend: function() {
                        $(ui.newHeader).next('div').html('<img src="/images/spinner.gif" alt="spnner gif" style="margin: 1.0em auto; display: block; width: 16px; height: 16px;">');
                    },
                    success: function(data) {
                        if (data.result == 'success') {
                            $(ui.newHeader).next('div').html(data.message);
                            already_loaded[$(ui.newHeader).data('category-id')] = 1;
                        }   
                    }
                });
            }
        }
    });

    $('body').on('click', '.comments', function(event) {
        event.preventDefault();

        $.ajax({
            url: '/user/establishmentComment',
            async: true,
            type: 'post',
            cache: false,
            dataType: 'json',
            data: {id: $(this).data('rating-id')},
            success: function(data) {
                if (data.result == 'success') {
                    $('#style-beer-modal .modal-body').html('<p>' + data.message + '</p>');
                    $('#style-beer-modal .modal-title').html(data.title);
                    $('#style-beer-modal').modal('show');
                }   
            }
        });
    });
});