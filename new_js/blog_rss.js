$(function() {
    $.ajax({
        url: '/page/blogRSS',
        async: true,
        type: 'post',
        beforeSend: function() {
            show_spinner('blogPosts');
        },
        success: function(data) {
            $('#blogPosts').html(data);
        }
    });
});