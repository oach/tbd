
		
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-sm-9 col-xs-12">
<?php
if (empty($establishment)) {
?>
				<h2 class="brown">Establishments</h2>
				<div class="alert alert-warning" role="alert">There are no records for the establishment requested.</div>
<?php	
}
else {
?>
				<h2 class="brown">
<?php
if (in_array($establishment->categoryID, [1, 4, 6])) {
?>					
					<a class="brown" href="<?php echo base_url(); ?>brewery/info/<?php echo $establishment->id; ?>/<?php echo $establishment->slug_establishment; ?>"><?php echo $establishment->name; ?></a>
<?php
}
else {
?>
					<?php echo $establishment->name; ?>
<?php
}

if (!empty($establishment->url)) {
?>
					<a href="<?php echo $establishment->url; ?>" target="_blank"><img src="/images/web.jpg" alt="<?php echo $establishment->name; ?> web site" title="<?php echo $establishment->name; ?> web site"></a>
<?php
}

if (!empty($establishment->breweryhopsID)) {
?>		
					<a href="<?php echo base_url(); ?>brewery/hop/<?php echo $establishment->breweryhopsID; ?>"><img src="/images/cone.gif" alt="brewery hop to <?php echo $establishment->name; ?>" title="brewery hop to <?php echo $establishment->name; ?>"></a>
<?php
}
?>			
					<a href="<?php echo base_url(); ?>establishment/googleMaps/<?php echo $establishmentID; ?>"><img src="/images/google-map.png" alt="map for <?php echo $establishment->name; ?>" title="map for <?php echo $establishment->name; ?>"></a>
			
					<?php echo $this->load->view('seo/twitter_establishment.php', array('twitter' => $establishment->twitter)); ?>
				</h2>
<?php					
}
?>
				<div class="row">
					<!--<div class="hidden-xs col-sm-5 col-md-5">-->
					<div class="col-xs-12 col-sm-5 col-sm-push-7 col-md-5 col-md-push-7">
						<div style="position: relative; margin-bottom: 10px; ">
							<?php echo $establishment_image['source']; ?>
							<?php echo array_key_exists('nub', $establishment_image) ? $establishment_image['nub'] : ''; ?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-7 col-sm-pull-5 col-md-7 col-md-pull-5">
<?php
	if ($logged && $establishment->closed != 1) {
?>
						<p>
							<a class="btn btn-primary" href="<?php echo base_url(); ?>establishment/createReview/<?php echo $establishment->id; ?>" role="button">Review</a>
						</p>
<?php
	}
?>	
						<p>
							<?php echo empty($establishment->address) ? '' : $establishment->address . '<br>'; ?>
							<?php echo empty($establishment->city) ? '' : '<a href="' . base_url() . 'establishment/city/' . $establishment->stateID . '/' . urlencode($establishment->city) . '">' . $establishment->city . '</a>, '; ?>
							<a href="<?php echo base_url(); ?>establishment/state/<?php echo $establishment->stateID; ?>"><?php echo $establishment->stateAbbr; ?></a>
							<?php echo empty($establishment->zip) ? '' : $establishment->zip; ?><br>
							<?php echo $this->phone->formatPhone($establishment->phone, true); ?>
						</p>
<?php
if ($rating_establishment->reviews > 0) {
?>
						<p>
							<span class="score_medium"><?php echo number_format($rating_establishment->averagereview, 1); ?></span>/10 by dude <?php echo ($rating_establishment->reviews > 1 ? $rating_establishment->reviews . 's' : $rating_establishment->reviews . ''); ?>
						</p>
						<p>
							<span class="bold">Dudes:</span>
<?php
	if (!empty($twobeerdudes)) {
?>		
							<span class="score_medium"><?php echo number_format($twobeerdudes->averagereview, 1); ?></span>/10
<?php							
	}
	else {
?>
							Not Rated
<?php
	}
?>
						</p>
						<p>
							<span class="bold">Price Index:</span>
							<span class="score_medium"><?php echo number_format($rating_establishment->averageprice, 1); ?></span>
							(<?php echo $this->pricing->getPriceLingo(((integer) (5 - round($rating_establishment->averageprice) + 1))); ?>)
						</p>
<?php
	$percentHaveAnother = number_format(($rating_establishment->averagevisitagain * 100));
?>						
						<p>
							<span class="bold">Visit Again:</span>
							<img style="vertical-align: middle;" src="/images/haveanother_<?php echo $percentHaveAnother >= 50 ? 'yes' : 'no'; ?>25.jpg" width="25" height="25" alt=""> 
							<span class="score_medium"><?php echo $percentHaveAnother; ?>%</span>
						</p>
<?php
	if (!empty($beers)) {
		$average = 'N/A';
		if ($beer_average->totalBeers > 0) {
			$average = round(($beer_average->totalPoints / $beer_average->totalBeers), 1);
		}
?>
						<ul class="green">
							<li class="bold" style="text-decoration: underline;">Beer Review Stats:</li>
							<li><span class="bold"><?php echo $beer_distinct_count->totalBeers; ?></span> different beer<?php echo $beer_distinct_count->totalBeers != 1 ? '' : 's'; ?></li>
							<li>drank <span class="bold"><?php echo $beer_average->totalBeers; ?></span> time<?php echo $beer_average->totalBeers != 1 ? '' : 's'; ?></li>
							<li>with a <span class="bold"><?php echo (is_numeric($average) ? number_format($average, 1) : '0.0'); ?></span> average rating</li>
							<li>and overall average cost of <span class="bold">$<?php echo number_format($average, 2); ?></span></li>
						</ul>
<?php						
	}
}
?>	
						<p>
							<!--<a href="<?php echo base_url(); ?>establishment/info/rating/<?php echo $establishment->id; ?>/<?php echo $establishment->slug_establishment; ?>">Establishment Reviews</a>-->
							<span>Establishment Reviews</span>
<?php					
if (in_array($establishment->categoryID, [1, 4, 6])) {
?>
							| <a href="<?php echo base_url(); ?>brewery/info/<?php echo $establishment->id; ?>/<?php echo $establishment->slug_establishment; ?>">Beer Reviews</a>
<?php
}
?>
						</p>
<?php
if ($establishment->closed == 1) {
?>
            			<div class="alert alert-danger" role="alert">Closed for business!</div>
<?php
}
?>				
					</div>
				</div>
<?php
if (!empty($ratings)) {
	foreach ($ratings as $rating) {
?>
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-12 col-sm-8 col-md-8">
								<ul>
									<li>
										<?php echo $rating->rating; ?>/10
										<img src="/images/haveanother_<?php echo $rating->visitAgain ? 'yes' : 'no'; ?>25.jpg" width="25" height="25" alt="have another">
									</li>
									<li>
										Quality: <span class="bold"><?php echo $rating->drink; ?></span> (<?php echo PERCENT_DRINK; ?>%) |
										Service: <span class="bold"><?php echo $rating->service; ?></span> (<?php echo PERCENT_SERVICE; ?>%) |
                                        Atmoshpere: <span class="bold"><?php echo $rating->atmosphere; ?></span> (<?php echo PERCENT_ATMOSPHERE; ?>%) |
                                        Pricing: <span class="bold"><?php echo $rating->pricing; ?></span> (<?php echo PERCENT_PRICING; ?>%) |
                                        Accessibility: <span class="bold"><?php echo $rating->accessibility; ?></span> (<?php echo PERCENT_ACCESSIBILITY; ?>%) |
                                        Overall: <span class="bold"><?php echo $rating->rating; ?></span>
									</li>
									<li><?php echo $this->load->view('establishment/info/pricing.php', array('rating' => $rating)); ?></li>
									<li>Reviewed: <?php echo $rating->formatDateAdded; ?></li>
									<li>
										Visited: <?php echo $rating->formatDateVisited; ?> by 
										<a href="<?php echo base_url(); ?>user/profile/<?php echo $rating->userID; ?>"><?php echo $rating->username; ?></a>
									</li>
									<li><?php echo nl2br($rating->comments); ?></li>							
								</ul>
                        	</div>
                        	<div class="hidden-xs col-sm-4 col-md-4">
								<a href="<?php echo base_url(); ?>user/profile/<?php echo $rating->userID; ?>">
									<img src="<?php echo base_url() . ($rating->avatar && !empty($rating->avatarImage) ? 'images/avatars/' . $rating->avatarImage : 'images/fakepic.png'); ?>">
								</a>
								<?php $this->load->view('establishment/info/review_average_by_user.php', array('rating' => $rating)); ?>
								<?php $this->load->view('establishment/info/review_similar.php', array('rating' => $rating)); ?>
							</div>
						</div>
					</div>
				</div>
<?php
	}
}
else {
?>
				<div class="alert alert-warning" role="alert">No ratings yet.</div>
<?php	
}
?>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-12">
    			<div class="side-info">
<?php
if (!empty($establishment)) {
	$this->load->view('seo/social_media_establishment.php');
}

$this->load->view('establishment/more.php');
?>
    			</div>
    		</div>
		</div>
	</div>	
<?php
/*			
			$config = array(
				'breweryName' => $establishment['name']
				, 'breweryCity' => $establishment['city']
				, 'breweryState' => $establishment['stateFull']
			);
			$seo = getDynamicSEO($config);
			$array = $seo + array('leftCol' => $str, 'rightCol' => $rightCol);*/
?>
