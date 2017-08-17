<?php
	//$rating = key_exists('rating', $config) ? $config['rating']['rating'] : set_value('slt_rating');
	$aroma = isset($form_data->aroma) ? $form_data->aroma : set_value('aroma');
	$taste = isset($form_data->taste) ? $form_data->taste : set_value('taste');
	$look = isset($form_data->look) ? $form_data->look : set_value('look');
	$mouthfeel = isset($form_data->mouthfeel) ? $form_data->mouthfeel : set_value('mouthfeel');
	$overall = isset($form_data->overall) ? $form_data->overall : set_value('overall');
	$dateTasted = isset($form_data->dateTasted) ? $form_data->dateTasted : set_value('dateTasted');
	$color = isset($form_data->color) ? $form_data->color : set_value('color');
	$comments = isset($form_data->comments) ? $form_data->comments : set_value('comments');
	$haveAnother = isset($form_data->haveAnother) ? $form_data->haveAnother : set_value('haveAnother');
	$packageID = isset($form_data->packageID) ? $form_data->packageID : set_value('package');
	$price = isset($form_data->price) ? $form_data->price : set_value('price');
?>	
	<form id="beer_review_form" class="edit" method="post" action="<?php echo base_url(); ?>beer/createReview/<?php echo $beer->id; ?>">
		<p class="bold" style="width: 100%;">Overall Rating: <span id="overallRating" class="required bold" style="text-align: right;"></span></p>
<?php
if (form_error('look')) {
?>
		<div class="text-danger"><?php echo form_error('look'); ?></div>		
<?php
}
?>	
		<div class="form-group">	
            <label for="look"><span class="required">*</span> Look (<?php echo PERCENT_LOOK; ?>% of the overall score): </label>
            <input type="text" class="form-control" id="look" name="look" placeholder="Look" data-slider-value="<?php echo $look; ?>" value="<?php echo $look; ?>">
        </div>
<?php
if (form_error('aroma')) {
?>
		<div class="text-danger"><?php echo form_error('aroma'); ?></div>		
<?php
}
?>	
		<div class="form-group">	
            <label for="aroma"><span class="required">*</span> Aroma (<?php echo PERCENT_AROMA; ?>% of the overall score): </label>
            <input type="text" class="form-control" id="aroma" name="aroma" placeholder="Aroma" data-slider-value="<?php echo $aroma; ?>" value="<?php echo $aroma; ?>">
            <span class="help-block"></span>
        </div>
<?php
if (form_error('taste')) {
?>
		<div class="text-danger"><?php echo form_error('taste'); ?></div>		
<?php
}
?>	
		<div class="form-group">	
            <label for="taste"><span class="required">*</span> Taste (<?php echo PERCENT_TASTE; ?>% of the overall score): </label>
            <input type="text" class="form-control" id="taste" name="taste" placeholder="Taste" data-slider-value="<?php echo $taste; ?>" value="<?php echo $taste; ?>">
            <span class="help-block"></span>
        </div>
<?php
if (form_error('mouthfeel')) {
?>
		<div class="text-danger"><?php echo form_error('mouthfeel'); ?></div>		
<?php
}
?>	
		<div class="form-group">	
            <label for="mouthfeel"><span class="required">*</span> Mouthfeel (<?php echo PERCENT_MOUTHFEEL; ?>% of the overall score): </label>
            <input type="text" class="form-control" id="mouthfeel" name="mouthfeel" placeholder="Mouthfeel" data-slider-value="<?php echo $mouthfeel; ?>" value="<?php echo $mouthfeel; ?>">
            <span class="help-block"></span>
        </div>        
<?php
if (form_error('overall')) {
?>
		<div class="text-danger"><?php echo form_error('overall'); ?></div>		
<?php
}
?>	
		<div class="form-group">	
            <label for="overall"><span class="required">*</span> Overall (<?php echo PERCENT_OVERALL; ?>% of the overall score): </label>
            <input type="text" class="form-control" id="overall" name="overall" placeholder="overall" data-slider-value="<?php echo $overall; ?>" value="<?php echo $overall; ?>">
            <span class="help-block"></span>
        </div>
<?php
if (form_error('dateTasted')) {
?>
		<div class="text-danger"><?php echo form_error('dateTasted'); ?></div>		
<?php
}
?>	
		<div class="form-group">	
            <label class="sr-only" for="dateTasted"><span class="required">*</span> Date Tasted:</label>
            <input type="text" class="form-control" id="dateTasted" name="dateTasted" readonly placeholder="Date Tasted: YYYY-MM-DD" value="<?php echo $dateTasted; ?>">
            <span class="help-block">Date is in yyyy-mm-dd format.  Please use calendar to auto select, it will format appropriately.</span>
        </div>
<?php
if (form_error('color')) {
?>
		<div class="text-danger"><?php echo form_error('color'); ?></div>		
<?php
}
?>	
		<div class="form-group">	
            <label class="sr-only" for="color"><span class="required">*</span> Color:</label>
            <input type="text" class="form-control" id="color" name="color" placeholder="Color" value="<?php echo $color; ?>">
            <span class="help-block">Try to use colors (or numbers) from the American <a href="<?php echo base_url(); ?>beer/srm">degrees SRM</a> scale.</span>
        </div>
<?php
if (form_error('comments')) {
?>
		<div class="text-danger"><?php echo form_error('comments'); ?></div>		
<?php
}
?>	
		<div class="form-group">	
            <label class="sr-only" for="comments"><span class="required">*</span> Comments:</label>
            <textarea class="form-control" id="comments" name="comments" rows="5" placeholder="Comments"><?php echo $comments; ?></textarea>
            <span class="help-block">Your thoughts about the beer.</span>
        </div>
<?php
if (form_error('haveAnother')) {
?>
		<div class="text-danger"><?php echo form_error('haveAnother'); ?></div>		
<?php
}

$select = $this->load->view('forms/select.php', array(
	'haveAnother' => $haveAnother,
	'select_data' => array(array('id' => '0', 'name' => 'No'), array('id' => '1', 'name' => 'Yes')),
	'select_class' => 'haveAnother'
), false);
?>	
		<div class="form-group">	
            <label class="sr-only" for="haveAnother"><span class="required">*</span> Have Another:</label>
			<?php echo $select; ?>
            <span class="help-block">Quite simply: would you have another one if presented with the chance.</span>
        </div>
<?php
if (form_error('package')) {
?>
		<div class="text-danger"><?php echo form_error('package'); ?></div>		
<?php
}

$select = $this->load->view('forms/select.php', array(
	'package' => $packageID,
	'select_data' => $packages,
	'select_class' => 'package'
), false);
?>	
		<div class="form-group">	
            <label class="sr-only" for="package"><span class="required">*</span> Package:</label>
			<?php echo $select; ?>
            <span class="help-block">The packaging format of the brew you are reviewing.</span>
        </div>
<?php
if (form_error('price')) {
?>
		<div class="text-danger"><?php echo form_error('price'); ?></div>		
<?php
}
?>	
		<div class="form-group">	
            <label class="sr-only" for="price"><span class="required">*</span> Price:</label>
            <input type="text" class="form-control" id="price" name="price" placeholder="Price" value="<?php echo $price; ?>">
            <span class="help-block">The price of the beer in dd.cc format.  Don't include the dollar sign ($) and for values under a dollar, use a zero (ex: 0.85).</span>
        </div>
			
		<input type="submit" class="btn btn-primary" id="submit" name="submit" value="Submit Beer Review">
	</form>

    <script>
    var percent_aroma = (<?php echo PERCENT_AROMA; ?> / 100);
    var percent_taste = (<?php echo PERCENT_TASTE; ?> / 100);
    var percent_look = (<?php echo PERCENT_LOOK; ?> / 100);
    var percent_mouthfeel = (<?php echo PERCENT_MOUTHFEEL; ?> / 100);
    var percent_overall = (<?php echo PERCENT_OVERALL; ?> / 100);
	</script>
    