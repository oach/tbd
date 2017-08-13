$(function()
{
	get_dude_list();
	
	$('body').on('click', '#add_dude', function(event)
	{
		event.preventDefault();
		$(this).closest('li').remove();
		
		$.ajax({
	        url: '/dude/addDude',
	        async: true,
	        type: 'post',
	        data: {id: $(this).data('id')},
	        beforeSend: function()
	        {
	            $('#dudeList').remove();
	        },
	        success: function(data)
	        {
	            $('.side-info').append(data);
	            $('#dudeList').show();
	            activate();
	        }
	    });
	});
});

function get_dude_list()
{
	$.ajax({
        url: '/dude/getList',
        async: true,
        type: 'post',
        cache: false,
        beforeSend: function()
        {
            $('#dudeList').remove();
            $('.side-info').append('<img src="/images/spinner.gif" id="spinner-dude-list" alt="spnner gif" style="margin: 1.0em auto; display: block; width: 16px; height: 16px;">');
        },
        success: function(data)
        {
            $('#spinner-dude-list').remove();
            $('.side-info').append(data);
            $('#dudeList').show();
            activate();
        }
    });
}

function activate()
{
	$('.dude-item-container').each(function (i, v)
    {
    	$(this).on('mouseover mouseout', function(event)
    	{
    		var t = $(this).children('button').eq(0);
    		$(t).toggle();
    	});
    });

    $('body').on('click', '.remove-dude', function(event)
	{
		event.preventDefault();
		$(this).closest('li').remove();

		if ($('#dude-list-group li').length < 1)
		{
			$('#dudeList').remove();
		}

		$.ajax({
	        url: '/dude/removeDude',
	        async: true,
	        type: 'post',
	        data: {id: $(this).data('id')}
	    });
	});
}