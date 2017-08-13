<div class="modal fade" id="upload-image-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Modal title</h4>
			</div>
			<div class="modal-body">
				<p>One fine body&hellip;</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script src="/js/jquery.cropit.0.5.1.js"></script>
<script>
$(function() {
  $('.image-editor').cropit();

  $('body').on('click', '#upload-image', function() {
    var image_data = $('.image-editor').cropit('export', {
      type: 'image/jpeg',
      quality: .3
    });
    $('.hidden-image-data').val(image_data);

    $('#crop-image-form').submit();
  });

  $('body').on('change', '.cropit-image-input', function() {
    console.log('val: ' + $(this).val());
    if ($(this).val()) {
      $('#upload-image, .cropit-preview, #cropit-image-zoom').show();
    }
    else {
      $('#upload-image, .cropit-preview, #cropit-image-zoom').hide();
    }
  });
});
</script>