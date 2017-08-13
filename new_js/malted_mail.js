
$(function()
{
    if (typeof(display_error) != 'undefined' && display_error) {
        display_no_messages();
    }

    $('body').on('keyup keydown focus', '#message', function() {
        calc_maxlength($(this));
    }).trigger('keyup');

    $('body').on('click', '.malted-remove-anchor', function(event) {
        event.preventDefault();

        var that = this;
        
        $.ajax({
            url: '/pms/delete',
            async: true,
            type: 'post',
            cache: false,
            dataType: 'json',
            data: {id: $(this).data('id')},
            beforeSend: function() {
                $(that).closest('.malted-message').html('<img src="/images/spinner.gif" alt="spnner gif" style="margin: 1.0em auto; display: block; width: 16px; height: 16px;">');
            },
            success: function(data) {
                if (data.result == 'success') {
                    $('#pms-count').html(data.count);
                    if (data.count < 1) {
                        $('#alert').removeClass().addClass('alert alert-warning').html('<p>No private messages in your inbox.</p>');
                        $('#malted-mail').html('');
                    } else {
                        $('#malted-mail').html(data.message);
                    }                    
                }                
            }
        });
    });

    if (typeof(pms) != 'undefined' && pms) {
        show_message_count(pms);
    }
});

function calc_maxlength(obj)
{
    var maxlength = obj.attr('maxlength');
    if (obj.val().length > maxlength) {
        obj.val(obj.val().substr(0, maxlength));
    }

    var remaining = maxlength - obj.val().length;
    $('#chars-remaining').text('Characters remaining: ' + remaining);
    toggle_classes($('#chars-remaining'), remaining);
}

function toggle_classes(obj, remaining)
{
    var cl = 'label label-default';
    if (remaining < 100) {
        cl = 'label label-danger';
    } else if (remaining < 500) {
        cl = 'label label-warning';
    } else if (remaining < 2000) {
        cl = 'label label-success';
    }
            
    $(obj).removeClass().addClass(cl);
}

function show_message_count(pms)
{
    if (pms) {
        $('h2.brown').children('span:first').remove();
        $('h2.brown').append(' <span id="malted-mail-info" class="malted-mail-info"><span class="label label-default" ><span id="pms-count">' + pms + '</span> message' + s + '</span></span>');
    }
}

function display_no_messages() {
    $('#alert').removeClass().addClass('alert alert-warning').html('<p>No private messages in your inbox.</p>');
} 
