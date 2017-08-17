	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<!--<div id="image-cropper">
					<div class="cropit-preview"></div>

					<input type="range" class="cropit-image-zoom-input">
					<input type="file" class="cropit-image-input">
					<div class="select-image-btn">Select new image</div>
				</div>-->
				<!--<form action="#">
      <div class="image-editor">
        <input type="file" class="cropit-image-input">
        <div class="cropit-preview"></div>
        <div class="image-size-label">
          Resize image
        </div>
        <input type="range" class="cropit-image-zoom-input">
        <input type="hidden" name="image-data" class="hidden-image-data" />
        <button type="submit">Submit</button>
      </div>
</form>-->
<form action="/user/test2" id="crop-image-form" enctype="multipart/form-data" method="post">
	<div class="image-editor">
		<label class="btn btn-warning btn-file">
			<span class="glyphicon glyphicon-camera" aria-hidden="true"></span> Browse <input type="file" class="cropit-image-input" style="display: none;">
		</label>
		<button type="submit" class="btn btn-info" id="upload-image" style="display: none;"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Upload</button>
		<div class="cropit-image-preview-container" id="cropit-image-preview-container" style="display: none;">
			<div class="cropit-image-preview"></div>
		</div>
		<div id="cropit-image-zoom-input" style="display: none;">
			<div class="image-size-label"> Resize image </div>
			<input type="range" class="cropit-image-zoom-input" style="width: 150px;">
		</div>
		<input type="hidden" name="image-data" class="hidden-image-data">
	</div>
</form>
<div id="tmp"></div>
			</div>
			<div class="col-md-3">
				<p>This is some text on the right.</p>
			</div>
		</div>
	</div>

	<!--<script src="/js/cropper.full.js"></script>-->
	<script src="/js/croppit.fll.js"></script>
	<script>
		/*$('#image-cropper').cropit();

// When user clicks select image button,
// open select file dialog programmatically
$('.select-image-btn').click(function() {
  $('.cropit-image-input').click();
});

// Handle rotation
$('.rotate-cw-btn').click(function() {
  $('#image-cropper').cropit('rotateCW');
});
$('.rotate-ccw-btn').click(function() {
  $('#image-cropper').cropit('rotateCCW');
});*/
	/*$(function() {
        $('.image-editor').cropit();
        $('form').submit(function() {
          // Move cropped image data to hidden input
          var imageData = $('.image-editor').cropit('export');
          $('.hidden-image-data').val(imageData);
          // Print HTTP request params
          var formValue = $(this).serialize();
          $('#result-data').text(formValue);
          // Prevent the form from actually submitting
          return false;
        });
      });*/
	$(function() {
        $('.image-editor').cropit({
          exportZoom: 1.25,
          done: function(data)
          {
          	console.log(data.width);
          }/*,
          imageBackground: true,
          imageBackgroundBorderSize: 100,
          imageState: {
            src: 'http://lorempixel.com/500/400/'
          }*/
        });

        $('.cropit-image-input').on('change', function()
        {
        	console.log($(this).val());
        	if ($(this).val())
        	{
        		$('#upload-image, #cropit-image-preview-container, #cropit-image-zoom-input').show();
        	}
        	else
        	{
        		$('#upload-image, #cropit-image-preview-container, #cropit-image-zoom-input').hide();
        	}
        });

        $('#crop-image-form').submit(function()
        {
        	//event.preventDefault();
        	var image_data = $('.image-editor').cropit('export', {
        		type: 'image/jpeg',
        		quality: .3
        	});
        	window.open(image_data);
        	/*var image_data = $('.image-editor').cropit('croppedImageData', {
        		type: 'image/jpeg',
        		quality: .3
        	});*/
        	/*console.log(image_data);
        	$('.hidden-image-data').val(image_data);
        	var form_value = $(this).serialize();
        	//$('#tmp').text(form_value);
        	//return false;

        	window.setTimeout(function()
        	{
        		$.ajax({
			        url: '/user/test2',
			        async: true,
			        type: 'post',
			        //data: {id: $(this).data('id')},
			        data: form_value,
			        beforeSend: function()
			        {
			            $('#crop-image-form').hide();
			        },
			        success: function(data)
			        {
			            console.log(data);
			            $('#tmp').html('<img src="' + data + '">');
			        }
			    });
        	}, 2000);*/
        	return false;
        });
	/*$('#upload-image').click(function()
        {
        	//event.preventDefault();
        	var image_data = $('.image-editor').cropit('export');
        	console.log(image_data);
        	$('.hidden-image-data').val(image_data);
        	var tmp = $(this).serialize();
        	console.log(tmp);
        	$('#tmp').text('this is some words.');
        	//$('#tmp').text(tmp);

        	window.setTimeout(function()
        	{
        		$.ajax({
			        url: '/user/test2',
			        async: true,
			        type: 'post',
			        //data: {id: $(this).data('id')},
			        data: $(this).serialize(),
			        beforeSend: function()
			        {
			            $('#crop-image-form').hide();
			        },
			        success: function(data)
			        {
			            console.log(data);
			        }
			    });
        		$('#crop-image-form').submit();
        	}, 1000);
        	return false;
        });*/
      });
	/*$(function()
	{
		var Cropper = window.Cropper;		

		var options = {
			preview: '.img-preview',
			ready: function (e) {
				console.log(e.type);
			}
		};
		var cropper = new Cropper(image, options);
	});*/
	</script>