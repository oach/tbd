
	$(function() {
		$('body').on('click', '.trend-modal', function(event) {
			event.preventDefault();

			$.ajax({
		        url: '/graph/graph.php',
		        async: true,
		        type: 'post',
		        data: {id: $(this).data('id')},
		        dataType: 'json',
		        success: function(data) {
		        	$('#beer-graph .modal-title').html($('h2.brown').text());
		        	if (data.type == 'success') {			            
			            $('#beer-graph .modal-body').html('<img src="data:image/png;base64,' + data.message + '">');			            
			            //$('#beer-graph .modal-body').html('<img src="/images/graph/' + data.message + '">');			            
			        }
			        else {
			        	$('#beer-graph .modal-body').html('<div class="alert alert-danger" role="alert">' + data.message + '</div>');
			        	setTimeout(function() {$('#beer-graph').modal('hide');}, 4000);
			        }
			        $('#beer-graph').modal('show');
	            }
		    });
		});

		/*$('body').on('click', '#update-beer-info', function(event)
		{
			event.preventDefault();

			$.ajax({
		        url: '/graph/graph.php',
		        async: true,
		        type: 'post',
		        data: {id: $(this).data('id')},
		        dataType: 'json',
		        success: function(data)
		        {
		        	$('.modal-title').html($('h2.brown').text());
		        	if (data.type == 'success') {			            
			            $('.modal-body').html('<img src="data:image/png;base64,' + data.message + '"/>');			            
			        }
			        else {
			        	$('.modal-body').html('<div class="alert alert-danger" role="alert">' + data.message + '</div>');
			        	setTimeout(function() {$('.modal').modal('hide');}, 4000);
			        }
			        $('.modal').modal('show');
	            }
		    });
		    $('.modal-title').html('eat shit');
		    $('.modal').modal('show');
		});*/

        $('body').on('click', '.swap', function(event) {
            event.preventDefault();

            $.ajax({
		        url: '/swaplist/add',
		        async: true,
		        type: 'post',
		        data: {id: $(this).data('id'), type: $(this).data('type')},
		        dataType: 'json',
		        success:  function(data) {
		        	if (data.type == 'success') {			            
			            $('#swap-list').html(data.message);			            
			        }
			        else {
			        	$('#swap-list').html(data.message);
			        }
	            }
		    });
        });        
    });
