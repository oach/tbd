var image_quality = $('input[name="type"]').val() == 'avatars' ? .9 : .8;
$(function() {
		$('.image-editor').cropit();

		$('body').on('click', '#upload-image', function() {
		var image_data = $('.image-editor').cropit('export', {
  			type: 'image/jpeg',
  			quality: image_quality
		});
		$('.hidden-image-data').val(image_data);

		$('#crop-image-form').submit();
		});      

	$('body').on('change', '.cropit-image-input', function() {
		if ($(this).val()) {
			$('#upload-image, .cropit-preview, #cropit-image-zoom').show();
		} else {
			$('#upload-image, .cropit-preview, #cropit-image-zoom').hide();
		}
	});
});

$('#crop-image-form').submit(function(event) {
	event.preventDefault();

	$('#crop-image-form').hide();
	$('#spinner').show();

	var image_data = $('.image-editor').cropit('export', {
		type: 'image/jpeg',
		quality: image_quality
	});
	$('.hidden-image-data').val(image_data);

	$('#upload-image').attr('disabled', 'disabled');
	
	var form_value = $(this).serialize();
	window.setTimeout(function() {
		$.ajax({
			url: '/page/saveUploadImage',
			async: true,
			type: 'post',
			data: form_value,
			processData: false,
			dataType: 'json',
			beforeSend: function() {
				$('#crop-image-form').hide();
			},
			success: function(data) {
				if (data.danger) {
					$('#upload-alert').html(data.danger).show();
				} else {
					window.location.href = 'http://' + window.location.hostname + '/' + data.uri;
				}
			}
		});
	}, 2000);
	return false;
});