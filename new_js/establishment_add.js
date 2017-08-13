
    $(function() {
        calc_phone_maxlength($('#phone'));
        $('body').on('keyup keydown focus', '#phone', function() {
        	calc_phone_maxlength($(this));
        }).trigger('keyup');

        $('#category').multiselect({
        	buttonWidth: '100%',
            nonSelectedText: 'Check one or more!'
            /*,
        	onChange: function(element, checked) {
        		if (checked == true) {
        			$('#category option[value=' + element.val() + ']').attr('selected', 'selected');
        		} else if (checked == false) {
        			$('#category option[value=' + element.val() + ']').removeAttr('selected');
        		}        		
        	}*/
        });
        //$('#category').find('option[value="1"]').prop('selected', 'selected');
        //$('#category').val('1');
        $('#category').multiselect('refresh');
    });

    function calc_phone_maxlength(obj) {
    	obj.val(obj.val().replace(/[^0-9]+/g, ''));

    	var maxlength = obj.data('maxlength');
    	if (obj.val().length > maxlength) {
    		obj.val((obj.val().substr(0, maxlength)));
    	}

    	var remaining = maxlength - obj.val().length;
	    $('#chars-remaining').text('Characters remaining: ' + remaining);
	    toggle_classes($('#chars-remaining'), remaining);
    }

    function toggle_classes(obj, remaining) {// console.log('remaining');
	    var cl = 'label label-default';
	    if (remaining < 1) {
	        cl = 'label label-danger';
	    }
	    else if (remaining <= 3) {
	        cl = 'label label-warning';
	    }
	    else if (remaining < 10) {
	        cl = 'label label-success';
	    }
	            
	    $(obj).removeClass().addClass(cl);
	}
