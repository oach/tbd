          
$(function() {
    $('#search-menu-text').autocomplete({
        minLength: 2,
        source: function(request, response) {
            $.ajax({
                url: '/page/searchAutoComplete/',
                data: {
                    term: $('#search-menu-text').val(),
                    search_type: $('#searchType').val()
                },
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            value: item.name,
                            url: item.url,
                            image: item.image
                        }
                    }))
                }
            });
        },
        select: function(event, ui) {
            window.location.href = ui.item.url;
            clear_search_text();
        },                        
    })
    .autocomplete('instance')._renderItem = function(ul, item) {
        return $('<li>')
            .append('<div class="row"><div class="col-xs-3 col-md-2">' + item.image + '</div><div class="col-xs-9 col-md-10">' + item.value + '</div><div>')
            .appendTo(ul);
    };
});

$(function() {
    $('body').on('click', '#search-toggle', function() {
        toggle_search_menu();
    });

    $('body').on('click', '#search-toggle-close', function() {
        toggle_search_menu();
        clear_search_text();
    });
});

function toggle_search_menu() {
    $('#search-menu').toggle();
}

function clear_search_text() {
    $('#search-menu-text').val('');
}