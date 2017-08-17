
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-9">	
				<div class="row">
					<div class="col-xs-4 col-sm-3 col-md-3">
						<!--<div style="background-image: url(<?php echo $beer_image['source']; ?>); background-size: 100% auto; background-position: left 20px; background-repeat: no-repeat; min-height: 370px;">
						<?php echo array_key_exists('nub', $beer_image) ? $beer_image['nub'] : ''; ?>
						</div>-->
						<div style="margin-top: 14px; position: relative;">
							<!--<img src="<?php echo $beer_image['source']; ?>" class="img-responsive">-->
							<?php echo $beer_image['source']; ?>
							<?php echo array_key_exists('nub', $beer_image) ? $beer_image['nub'] : ''; ?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-9 col-md-9">
						<h2 class="brown">
							<?php $this->load->view('beer/update_links.php');
							echo $beer_info->beerName; ?>
						</h2>
<?php
if (!empty($beer_info->alias)) {
?>
						<p class="alias brown">Formally known as: <?php echo $beer_info->alias; ?></p>
<?php
}

if ($beer_info->retired && $beer_info->closed) {
?>
						<div class="alert alert-danger" role="alert">Retired, no longer in production AND the brewery is closed</div>
<?php
} elseif ($beer_info->retired) {
?>	
						<div class="alert alert-danger" role="alert">Retired, no longer in production</div>
<?php        
} elseif ($beer_info->closed) {
?>	
						<div class="alert alert-danger" role="alert">Retired, the brewery is no longer open</div>
<?php    
}
?>						
						<div class="establishmentLocation bold">
                            <p>
                                    <a href="<?php echo base_url(); ?>brewery/info/<?php echo $beer_info->establishmentID . '/' . $beer_info->slug_establishment; ?>">
                                    	<?php echo $beer_info->name; ?>
                                    </a>
<?php
if (!empty($beer_info->url)) {
?>                                     
									<a href="<?php echo $beer_info->url; ?>" target="_blank">
										<img src="/images/web.jpg" title="<?php echo $beer_info->name; ?> web site" alt="<?php echo $beer_info->name; ?> web site">
									</a>
<?php
}

if (!empty($beer_info->breweryhopsID)) {
?>	
									<a href="<?php echo base_url(); ?>brewery/hop/<?php echo $beers->breweryhopsID; ?>">
										<img src="/images/cone.gif" title="brewery hop to <?php echo $beer_info->name; ?>" alt="brewery hop to <?php echo $beer_info->name; ?>">
									</a>
<?php
}
?>
                                    <a href="<?php echo base_url(); ?>establishment/googleMaps/<?php echo $beer_info->establishmentID; ?>">
                                    	<img src="/images/google-map.png" alt="map for <?php echo $beer_info->name; ?>" title="map for <?php echo $beer_info->name; ?>">
                                    </a>
                                    <?php $this->load->view('seo/twitter_establishment', array('twitter' => $beer_info->twitter)); ?>
                            </p>
                            <p>
                                    <a href="<?php echo base_url(); ?>establishment/city/<?php echo $beer_info->stateID; ?>/<?php echo urlencode($beer_info->city); ?>">
                                    	<?php echo $beer_info->city; ?>
                                    </a>,
                                    <a href="<?php echo base_url(); ?>establishment/state/<?php echo $beer_info->stateID; ?>"><?php echo $beer_info->stateAbbr; ?></a>
                            </p>
                        </div>

                        <p>
                        	<span class="bold">Overall:</span> 
<?php
if (isset($ratingTotalTimes) && $ratingTotalTimes > 0) {
?>							
							<span class="big-text label<?php echo $this->rating->getRatingsLabelClass($ratingAverage); ?>"><?php echo $ratingAverage; ?>/10</span> by 
							<?php echo $ratingTotalTimes > 1 ? $ratingTotalTimes . ' dudes' : $ratingTotalTimes . ' dude'; ?>
<?php
} else {
?>
							No Ratings
<?php                        
}
?>                    	
						</p>
                        <p>
                        	<span class="bold">Dudes:</span> 
<?php
if (!empty($twoBeerDudes) && $twoBeerDudes->avergeRating > 0) {
?>
							<span class="big-text label<?php echo $this->rating->getRatingsLabelClass($twoBeerDudes->avergeRating); ?>"><?php echo number_format($twoBeerDudes->avergeRating, 1); ?>/10</span>
<?php
} else {
?>
							Not Rated
<?php
}
?>
                        </p>
<?php
if (isset($ratingTotalTimes) && $ratingTotalTimes > 0) {
?>                        
                        <p>
                        	<span class="bold">Trend:</span>
							<span class="big-text label<?php echo $this->rating->getRatingsLabelClass($trending->trendrating); ?>"><?php echo number_format($trending->trendrating, 1); ?>/10</span>
                        	over past <?php echo TREND_BEER_RATING_LIMIT; ?> reviews 
                        	<a href="#" class="trend-modal" data-id="<?php echo $beer_info->id; ?>">
                        	<img src="/images/chart1.png" /></a>
                        </p>
<?php
}
?>                        
                        <p><span class="bold">Style:</span> <a href="<?php echo base_url(); ?>beer/style/<?php echo $beer_info->styleID; ?>"><?php echo $beer_info->style; ?></a></p>
<?php
if (!empty($beer_info->alcoholContent)) {
?>
						<p><span class="bold">ABV:</span> <?php echo $beer_info->alcoholContent; ?>%</p>
<?php						
}

if (!empty($beer_info->ibu)) {
?>
						<p><span class="bold">IBU:</span> <?php echo $beer_info->ibu; ?></p>
<?php
}

if ($beer_info->seasonal) {
?>
						<p><span class="bold">Season:</span> <?php echo $beer_info->seasonalPeriod; ?></p>
<?php		
} else {
?>
						<p><span class="bold">Season:</span> Year Round</p>
<?php		
}

if (isset($haveAnother) && $haveAnother !== false) {
?>	
						<p>
							<span class="bold">Have Another:</span>
							<img style="vertical-align: middle;" src="/images/haveanother_<?php echo $haveAnother >= 50 ? 'yes' : 'no'; ?>25.jpg" width="25" height="25" alt=""> 
							<?php echo $haveAnother; ?>%
						</p>                                
<?php
}

$this->load->view('beer/review_links.php');
?>

                    </div>
				</div>
<?php
if (count($beers) > 0) {
	foreach ($beers as $beer) {
?>
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-4 col-sm-push-8 col-md-4">
                        		<div class="row">
                        			<div class="col-xs-3 col-sm-3 col-md-3">
										<a href="<?php echo base_url(); ?>user/profile/<?php echo $beer->userID; ?>">
											<img src="<?php echo base_url() . ($beer->avatar && !empty($beer->avatarImage) ? 'images/avatars/' . $beer->avatarImage : 'images/fakepic.png'); ?>" width="40" height="40">
										</a>
									</div><!--hidden-xs-->
									<div class="col-xs-9 col-sm-9 col-md-9">
										<?php $this->load->view('beer/review_average_by_user.php', array('beer' => $beer)); ?>
									</div>
								</div>
								<?php $this->load->view('beer/review_similar.php', array('beer' => $beer)); ?>
							</div>
							<div class="col-sm-8 col-sm-pull-4 col-md-8">
								<ul class="beer-review-info list-unstyled">
									<li>
										<span class="bigger-text label<?php echo $this->rating->getRatingsLabelClass($beer->rating); ?>"><?php echo $beer->rating; ?>/10</span>
										<img src="/images/haveanother_<?php echo $beer->haveAnother ? 'yes' : 'no'; ?>25.jpg" width="25" height="25" alt="have another">
									</li>
									<li>
										<span class="label<?php echo $this->rating->getRatingsLabelClass($beer->aroma); ?>" data-toggle="tooltip" data-placement="top" title="Aroma - (<?php echo PERCENT_AROMA; ?>%)">A: <span class="bold"><?php echo $beer->aroma; ?></span></span> |
										<span class="label<?php echo $this->rating->getRatingsLabelClass($beer->taste); ?>" data-toggle="tooltip" data-placement="top" title="Taste - (<?php echo PERCENT_TASTE; ?>%)">T: <span class="bold"><?php echo $beer->taste; ?></span></span> |
                                        <span class="label<?php echo $this->rating->getRatingsLabelClass($beer->look); ?>" data-toggle="tooltip" data-placement="top" title="Look - (<?php echo PERCENT_LOOK; ?>%)">L: <span class="bold"><?php echo $beer->look; ?></span></span>  |
                                        <span class="label<?php echo $this->rating->getRatingsLabelClass($beer->mouthfeel); ?>" data-toggle="tooltip" data-placement="top" title="Mouthfeel - (<?php echo PERCENT_MOUTHFEEL; ?>%)">M: <span class="bold"><?php echo $beer->mouthfeel; ?></span></span> |
                                        <span class="label<?php echo $this->rating->getRatingsLabelClass($beer->overall); ?>" data-toggle="tooltip" data-placement="top" title="Overall - (<?php echo PERCENT_OVERALL; ?>%)">O: <span class="bold"><?php echo $beer->overall; ?></span></span><!--  |
                                        <span class="label<?php echo $this->rating->getRatingsLabelClass($beer->rating); ?>" data-toggle="tooltip" data-placement="top" title="Overall Rating">O: <span class="bold"><?php echo $beer->rating; ?></span></span>-->
									</li>
									
<?php
		if ($beer->shortrating == '1') {
?>			
									<!--<li>
										<span class="bold">Short Rating</span>
										Aroma: <span class="bold"><?php echo $beer->aroma; ?></span> (<?php echo PERCENT_AROMA; ?>%) |
										Taste: <span class="bold"><?php echo $beer->taste; ?></span> (<?php echo PERCENT_TASTE; ?>%) |
                                        Look: <span class="bold"><?php echo $beer->look; ?></span> (<?php echo PERCENT_LOOK; ?>%) |
                                        Mouthfeel: <span class="bold"><?php echo $beer->mouthfeel; ?></span> (<?php echo PERCENT_MOUTHFEEL; ?>%) |
                                        Overall: <span class="bold"><?php echo $beer->overall; ?></span> (<?php echo PERCENT_OVERALL; ?>%) |
                                        <span class="bold">Overall: <?php echo $beer->rating; ?></span>
									</li>-->
<?php									
		} else {
			$hex = $this->srm->convert_string_to_hex($beer->color);
			$hex_str = '';
			if ($hex['hex'] != '#fff') {
				$hex_str = ' class="badge" style="background-color: ' . $hex['hex'] . '; color: ' . $hex['color'] . ';"';
			}
?>
									<li style="margin: 10px 0;">Color: <span<?php echo $hex_str; ?>><?php echo $beer->color; ?></span></li>
									<li><?php echo nl2br($beer->comments); ?></li>							
<?php
		}
?>
									<li style="margin-top: 20px; font-size: 86%; color: #777;">Reviewed: <?php echo $beer->formatDateAdded; ?></li>
									<li style="font-size: 86%; color: #777;">
										Tasted: <?php echo $beer->formatDateTasted; ?> by 
										<a href="<?php echo base_url(); ?>user/profile/<?php echo $beer->userID; ?>"><?php echo $beer->username; ?></a>
									</li>
									<li style="font-size: 86%; color: #777;">$<?php echo $beer->price; ?> for <?php echo $beer->package; ?></li>
								</ul>
                        	</div>
                        	
						</div>
					</div>
				</div>
<?php				
	}
} else {
?>
				<div class="alert alert-warning" id="alert" role="alert">There are no reviews for this beer.</div>
<?php
}
?>

<?php			
     /*



            // get the ins and outs totals
            $insOuts = $this->ci->SwapModel->getInsAndOutsByBeerID($id);
            // check if this is an ajax call back
            if($ajax === false) {
                    // create the url based on if they are logged in
                    $ins = $outs = ' href="' . base_url() . 'user/login"';
                    if($userInfo != false) {
                            $ins = ' href="#" onclick="new Ajax.Request(\'' . base_url() . 'ajax/swapadd/ins/' . $id . '\', {asynchronous: true, evalScripts: true, method: \'get\', onLoading: function() {showSpinner(\'swapsInfo\');}, onComplete: function(response) {$(\'swapsInfo\').update(response.responseText);}}); return false;"';
                            $outs = ' href="#" onclick="new Ajax.Request(\'' . base_url() . 'ajax/swapadd/outs/' . $id . '\', {asynchronous: true, evalScripts: true, method: \'get\', onLoading: function() {showSpinner(\'swapsInfo\');}, onComplete: function(response) {$(\'swapsInfo\').update(response.responseText);}}); return false;"';
                    }
                    $str_rightCol .= '
                                            <ul>
                                                    <li><a' . $ins . '>add to swap ins</a></li>
                                                    <li><a' . $outs . '>add to swap outs</a></li>
                                            </ul>
                    ';
            }

            $str_rightCol .= '
                                            <p>
                                                    <a href="' . base_url() . 'beer/swaps/ins/' . $id . '"><span class="bold">' . $insOuts['ins'] . '</span></a> swap ins and 
                                                    <a href="' . base_url() . 'beer/swaps/outs/' . $id . '"><span class="bold">' . $insOuts['outs'] . '</span></a> swap outs
                                            </p>			
            ';
	

                    // get configuration values for creating the seo
                    $config = array(
                            'beerName' => $beers[0]['beerName']
                            , 'beerStyle' => $beers[0]['style']
                            , 'breweryName' => $beers[0]['name']
                            , 'breweryCity' => $beers[0]['city']
                            , 'breweryState' => $beers[0]['stateFull']
                    );
                    // set the page information
                    $seo = getDynamicSEO($config);
                    $array = $seo + array('str' => $str, 'str_rightCol' => $str_rightCol);
            }	
    }*/

?>
				<?php $this->load->view('beer/modal.php'); ?>
			</div>
            
            <div class="col-xs-12 col-sm-12 col-md-3">
    			<div class="side-info">
    				
<?php
$this->load->view('seo/social_media_beer.php');


if (!empty($beer_info->beerNotes)) {
?>
					 <div class="panel panel-default">
				        <div class="green bold panel-heading">Beer Notes</div>
				        <ul class="list-group">
        					<li class="list-group-item"><?php echo nl2br($beer_info->beerNotes); ?></li>
        				</ul>
				    </div>
<?php				    
}

if (isset($avgCost)  && count($avgCost) > 0) {
?>				    
				    <div class="panel panel-default">
				        <div class="green bold panel-heading">Cost Breakdown</div>
				        <ul class="list-group">
<?php
	foreach ($avgCost as $cost) {
?>
							<li class="list-group-item">
								$<?php echo $cost->averagePrice; ?>, <?php echo $cost->totalServings; ?> serving(s), <?php echo $cost->package; ?>s
							</li>
<?php
	}
?>	
						</ul>
				    </div>
<?php				    
}
?>
					<div class="panel panel-default">
				        <div class="green bold panel-heading">Beer Swapping</div>
				        <ul class="list-group" id="swap-list">
<?php $this->load->view('swaplist/beer.php'); ?>
						</ul>
				    </div>
<?php				    

if (!empty($beer_info->malts)) {
?>
					<div class="panel panel-default">
				        <div class="green bold panel-heading">Malts</div>
				        <ul class="list-group">
				        	<li class="list-group-item"><?php $beer_info->malts; ?>
						</ul>
				    </div>
<?php
}

if (!empty($beer_info->hops)) {
?>
					<div class="panel panel-default">
				        <div class="green bold panel-heading">Hops</div>
				        <ul class="list-group">
				        	<li class="list-group-item"><?php $beer_info->hops; ?>
						</ul>
				    </div>
<?php
}

if (!empty($beer_info->yeast)) {
?>
					<div class="panel panel-default">
				        <div class="green bold panel-heading">Yeast</div>
				        <ul class="list-group">
				        	<li class="list-group-item"><?php $beer_info->yeast; ?>
						</ul>
				    </div>
<?php
}

if (!empty($beers_info->glassware)) {
?>
					<div class="panel panel-default">
				        <div class="green bold panel-heading">Glassware</div>
				        <ul class="list-group">
				        	<li class="list-group-item"><?php $beer_info->glassware; ?>
						</ul>
				    </div>
<?php
}

if (!empty($beers->food)) {
?>
					<div class="panel panel-default">
				        <div class="green bold panel-heading">Food</div>
				        <ul class="list-group">
				        	<li class="list-group-item"><?php $beer_info->food; ?>
						</ul>
				    </div>
<?php
}

if (count($similarBeers) > 0) {
?>				    
				    <div class="panel panel-default">
				        <div class="green bold panel-heading">Highest Rated Similar Beers</div>
				        <ul class="list-group">
<?php
	foreach ($similarBeers as $similar) {
?>
							<li class="list-group-item">
								<!--<span class="label label-default"><?php echo $similar->avgRating; ?></span>-->
								<span class="label<?php echo $this->rating->getRatingsLabelClass($similar->avgRating); ?>"><?php echo $similar->avgRating; ?></span>
								<span><a href="<?php echo base_url(); ?>beer/review/<?php echo $similar->id . '/' . $similar->slug_beer; ?>"><?php echo $similar->beerName; ?></a></span>
								<span>by <a href="<?php echo base_url(); ?>brewery/info/<?php echo $similar->establishmentID . '/' . $similar->slug_establishment; ?>"><?php echo $similar->name; ?></a></span>
							</li>
<?php
	}
?>
				        </ul>
				    </div>
<?php
}
?>				    
    			</div>
    		</div>
        </div>
	</div>
