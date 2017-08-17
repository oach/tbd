<!--	<div class="container">
		<div class="row">
			<div class="col-md-9">-->

<form action="/user/test2" id="crop-image-form" enctype="multipart/form-data" method="post">
<!--<form id="crop-image-form" enctype="multipart/form-data">-->
<!--<form id="crop-image-form">-->
      <div class="image-editor">
          <label class="btn btn-warning btn-file">
            <span class="glyphicon glyphicon-camera" aria-hidden="true"></span> Browse <input type="file" class="cropit-image-input" style="display: none;">
          </label>
          <button type="submit" class="btn btn-info" id="upload-image" style="display: none;"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Upload</button>
          <div class="cropit-preview" style="display: none;"></div>
          <div id="cropit-image-zoom" style="display: none;">
            <div class="image-size-label">Resize image</div>
              <input type="range" class="cropit-image-zoom-input">
            </div>
          <input type="hidden" name="image-data" class="hidden-image-data">

<!--<input type="file" class="cropit-image-input">
<div class="cropit-preview"></div>
<div class="image-size-label">
Resize image
</div>
<input type="range" class="cropit-image-zoom-input">
<button class="rotate-ccw">Rotate counterclockwise</button>
<button class="rotate-cw">Rotate clockwise</button>

<button class="export">Export</button>-->
</div>
</form>
<div id="tmp"></div>
			<!--</div>
			<div class="col-md-3">
				<p>This is some text on the right.</p>
			</div>
		</div>
	</div>-->

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

//$('#upload-image').click(function()
      /*$('#crop-image-form').submit(function()
      {
          var image_data = $('.image-editor').cropit('export', {
            type: 'image/jpeg',
            quality: .3
          });
          //console.log(image_data);
          //return false;

          $('.hidden-image-data').val(image_data);
          //var form_value = $('#crop-image-form').serialize();
          var form_value = $(this).serialize();
          //console.log(form_value);
          //return false;

          window.setTimeout(function()
          {
            $.ajax({
              url: '/user/test2',
              async: true,
              type: 'post',
              //data: {id: $(this).data('id')},
              //data: form_value,
              processData: false,
              beforeSend: function()
              {
                  $('#crop-image-form').hide();
              },
              success: function(data)
              {
                  //console.log(data);
                  $('#tmp').html('<img src="' + data + '">');
              }
          });
          }, 2000);
          return false;
      });*/
</script>