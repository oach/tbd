
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-sm-9 col-xs-12">
<?php
if (isset($error)) {
?>
				<h2 class="brown">Upload Image</h2>
				<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
<?php	
}	
else {
?>
				<h2 class="brown"><?php $this->load->view('page/upload/page_header.php'); ?></h2>
				<div id="upload-alert" class="alert alert-danger" role="alert" style="display: none;"></div>
				<div id="spinner" style="display: none;" class="text-center"><img src="/images/spinner.gif"></div>
				<form id="crop-image-form" enctype="multipart/form-data" method="post">
					<div class="image-editor">
						<label class="btn btn-warning btn-file">
							<span class="glyphicon glyphicon-camera" aria-hidden="true"></span> Browse <input type="file" class="cropit-image-input" style="display: none;">
						</label>
						<button type="submit" class="btn btn-info" id="upload-image" style="display: none;">
							<span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Upload
						</button>
						<div class="cropit-preview" style="display: none;"></div>
						<div id="cropit-image-zoom" style="display: none;">
							<div class="image-size-label">Resize image</div>
							<input type="range" class="cropit-image-zoom-input">
						</div>
						<input type="hidden" name="image-data" class="hidden-image-data">
						<input type="hidden" name="type" value="<?php echo $type; ?>">
						<input type="hidden" name="id" value="<?php echo $id; ?>">

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
			</div>
			<div class="col-md-3 col-sm-3 col-xs-12">
    			<div class="side-info">
    				<div class="panel panel-default">
				        <div class="green bold panel-heading">Upload Hints</div>
				        <ul class="list-group">
        					<li class="list-group-item">Only jpg, png, and gif images can be uploaded.  These file extensions are all associated with image files.</li>
        					<li class="list-group-item">Memory is not unlimited on our server, so...</li>
        					<li class="list-group-item">Max <span class="bold">file size</span> is <span class="bold">200 megabytes</span>.</li>
        					<li class="list-group-item">Max <span class="bold">image width</span> is <span class="bold">600 pixels</span>.</li>
        					<li class="list-group-item">Max <span class="bold">image height</span> is <span class="bold">1050 pixels</span>.</li>
        					<li class="list-group-item"><span class="bold">note:</span> gimp is an open source, free image processing software if you already don&#39;t have this type of software.  This software will help you manipulate your images to fit the upload criteria.</li>
        				</ul>
				    </div>

				    <div class="panel panel-default">
				        <div class="green bold panel-heading">Final Image Sizes</div>
				        <ul class="list-group">
        					<li class="list-group-item"><span class="bold">avatars:</span> 100 pixels by 100 pixels.</li>
        					<li class="list-group-item"><span class="bold">beers:</span> 150 pixels by 350 pixels.</li>
        					<li class="list-group-item"><span class="bold">establishments:</span> 240 pixels by 160 pixels.</li>
        					<li class="list-group-item"><span class="bold">note:</span> images you upload do not need to be this size, the second step, cropping will help accomplish this.</li>
        				</ul>
				    </div>
    			</div>
    		</div>
		</div>
	</div>
<?php
}
?>