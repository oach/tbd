
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-sm-9 col-xs-12">
				<h2 class="brown"><?php echo empty($rating) ? '' : 'Edit '; ?>Establishment Review for <?php echo $establishment->name; ?></h2>
<?php
//echo isset($possible) ? $possible : 'not set'; exit;
if ($reviewCount < MIN_REVIEW_COUNT_FOR_ESTABLISHMENT) {
?>
				<div class="alert alert-danger">
					You haven't reviewed enough beers (you need <?php echo MIN_REVIEW_COUNT_FOR_ESTABLISHMENT; ?> reviews) to add a new beer.
				</div>
<?php
}
else
{
	$drink = !empty($rating) ? $rating->drink : set_value('drink');
    $service = !empty($rating) ? $rating->service : set_value('service');
    $atmosphere = !empty($rating) ? $rating->atmosphere : set_value('atmosphere');
    $pricing = !empty($rating) ? $rating->pricing : set_value('pricing');
    $accessibility = !empty($rating) ? $rating->accessibility : set_value('accessibility');
	$dateVisited = !empty($rating) ? $rating->dateVisited : set_value('dateVisited');
	$comments = !empty($rating) ? $rating->comments : set_value('comments');
	$visitAgain = !empty($rating) ? $rating->visitAgain : set_value('visitAgain');
	$price = !empty($rating) ? $rating->price : set_value('price');
?>
	<form id="establishment_review_form" class="edit" method="post" action="<?php echo base_url(); ?>establishment/createReview/<?php echo $establishmentID; ?>">
		<p class="bold" style="width: 100%;">Overall Rating: <span id="overallRating" class="required bold" style="text-align: right;"></span></p>
<?php
if (form_error('drink')) {
?>
		<div class="text-danger"><?php echo form_error('drink'); ?></div>		
<?php
}
?>	
		<div class="form-group" data-toggle="tooltip" data-placement="top" title="What was the quality of the selection, taps, food, drink, bottles, etc.">	
            <label for="drink"><span class="required">*</span> Drink/Food (<?php echo PERCENT_DRINK; ?>% of the overall score):</label>
            <input type="text" class="form-control" id="drink" name="drink" placeholder="Drink" data-slider-value="<?php echo $drink; ?>" value="<?php echo $drink; ?>">
        </div>
<?php
if (form_error('service')) {
?>
		<div class="text-danger"><?php echo form_error('service'); ?></div>		
<?php
}
?>	
		<div class="form-group" data-toggle="tooltip" data-placement="top" title="Did you feel the staff took care of you well?  Were they friendly? Helpful?">	
            <label for="service"><span class="required">*</span> Service (<?php echo PERCENT_SERVICE; ?>% of the overall score):</label>
            <input type="text" class="form-control" id="service" name="service" placeholder="Service" data-slider-value="<?php echo $service; ?>" value="<?php echo $service; ?>">
        </div>
<?php
if (form_error('atmosphere')) {
?>
		<div class="text-danger"><?php echo form_error('atmosphere'); ?></div>		
<?php
}
?>	
		<div class="form-group" data-toggle="tooltip" data-placement="top" title="Was the place clean?  Could you hear each other talk?  If you have kids, was it kid friendly?">	
            <label for="atmosphere"><span class="required">*</span> Atmosphere (<?php echo PERCENT_ATMOSPHERE; ?>% of the overall score):</label>
            <input type="text" class="form-control" id="atmosphere" name="atmosphere" placeholder="Atmosphere" data-slider-value="<?php echo $atmosphere; ?>" value="<?php echo $atmosphere; ?>">
        </div>
<?php
if (form_error('pricing')) {
?>
		<div class="text-danger"><?php echo form_error('pricing'); ?></div>		
<?php
}
?>	
		<div class="form-group" data-toggle="tooltip" data-placement="top" title="Based on what you paid for drink, food, souvenirs, etc how well does it compare in terms of value?  10 is a bargain, while 1 is high pricing.">	
            <label for="pricing"><span class="required">*</span> Pricing (<?php echo PERCENT_PRICING; ?>% of the overall score):</label>
            <input type="text" class="form-control" id="pricing" name="pricing" placeholder="Pricing" data-slider-value="<?php echo $pricing; ?>" value="<?php echo $pricing; ?>">
        </div>  
<?php
if (form_error('accessibility')) {
?>
		<div class="text-danger"><?php echo form_error('accessibility'); ?></div>		
<?php
}
?>	
		<div class="form-group" data-toggle="tooltip" data-placement="top" title="How easy is it to get to?  Is there a wait to get in?">	
            <label for="accessibility"><span class="required">*</span> Accessibility (<?php echo PERCENT_ACCESSIBILITY; ?>% of the overall score):</label>
            <input type="text" class="form-control" id="accessibility" name="accessibility" placeholder="Accessibility" data-slider-value="<?php echo $accessibility; ?>" value="<?php echo $accessibility; ?>">
        </div>
<?php
if (form_error('dateVisited')) {
?>
		<div class="text-danger"><?php echo form_error('dateVisited'); ?></div>		
<?php
}
?>	
		<div class="form-group">	
            <label class="sr-only" for="dateVisited"><span class="required">*</span> Date Visited:</label>
            <input type="text" class="form-control" id="dateVisited" name="dateVisited" readonly placeholder="Date Tasted: YYYY-MM-DD" value="<?php echo $dateVisited; ?>">
            <span class="help-block">Date is in yyyy-mm-dd format.  Please use calendar to auto select, it will format appropriately.</span>
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
            <span class="help-block">Your thoughts about the establishment.</span>
        </div>
<?php
if (form_error('visitAgain')) {
?>
		<div class="text-danger"><?php echo form_error('visitAgain'); ?></div>		
<?php
}

$select = $this->load->view('forms/select.php', array(
	'visitAgain' => $visitAgain,
	'select_data' => array(array('id' => '0', 'name' => 'No'), array('id' => '1', 'name' => 'Yes')),
	'select_class' => 'visitAgain'
), false);
?>	
		<div class="form-group">	
            <label class="sr-only" for="visitAgain"><span class="required">*</span> Visit Again:</label>
			<?php echo $select; ?>
            <span class="help-block">Quite simply: would you visit again if presented with the chance.</span>
        </div>        

        <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Submit Establishment Review">
    </form>
<?php
}
?>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-12">
    			<div class="side-info">
    				<div class="panel panel-default">
                        <div class="green bold panel-heading">Tips on Reviewing</div>
                        <ul class="list-group">
		                    <li class="list-group-item">10s should be few and far between: the establishmet is perfect and needs no improvement.</li>
		                    <li class="list-group-item">Reviews should be recent and, preferrably, from handwritten notes as it is sometimes difficult to remember the caveats of an establishment.</li>
		                    <li class="list-group-item">Try to review an establishment with an appreciation of what the owners are trying to accomplish.</li>
		                    <li class="list-group-item">Have fun with your review (within reason) let your personality show through.</li>
		                    <li class="list-group-item">This is not a race to see who can review the most beers in a day, week, month, year, etc.  Like your beer, enjoy the experience.</li>
                        </ul>
                    </div>
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Rules of Reviewing</div>
                        <ul class="list-group">
		                    <li class="list-group-item">Only <span class="bold">American establishments</span> should be reviewed here.</li>
		                    <li class="list-group-item">Use <span class="bold">English</span>!</li>
		                    <li class="list-group-item">Some people are offended by off language, keep it to a minimum.</li>
		                    <li class="list-group-item">Don&#39;t slam another user for their review.  This is suppose to be fun, keep it that way.</li>
                        </ul>
                    </div>
    			</div>
    		</div>
		</div>
	</div>

				
				
<?php
/*	
if(empty($_POST) && !empty($rating)) {
					// get the form		
					$form = form_estblishmentReview(array('id' => $id, 'rating' => $rating));
				} else {
					// get the form
					$form = form_estblishmentReview(array('id' => $id));
				}

$config = array(
	'breweryName' => $establishments['name']
	, 'breweryCity' => $establishments['city']
	, 'breweryState' => $establishments['stateFull']
	, 'seoType' => 'reviewEstablishment'
);
$seo = getDynamicSEO($config);
*/
?>

	
	<script>
    var percent_drink = (<?php echo PERCENT_DRINK; ?> / 100);
    var percent_service = (<?php echo PERCENT_SERVICE; ?> / 100);
    var percent_atmoshpere = (<?php echo PERCENT_ATMOSPHERE; ?> / 100);
    var percent_pricing = (<?php echo PERCENT_PRICING; ?> / 100);
    var percent_accessibility = (<?php echo PERCENT_ACCESSIBILITY; ?> / 100);
	</script>
