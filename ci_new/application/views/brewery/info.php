	
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
					<a class="brown" href="<?php echo base_url(); ?>brewery/info/<?php echo $establishment->id; ?>/<?php echo $establishment->slug_establishment; ?>"><?php echo $establishment->name; ?></a>
<?php
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
				<div class="row">
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
							<a class="btn btn-primary" href="<?php echo base_url(); ?>beer/addBeer/<?php echo $establishment->id; ?>" role="button">Add a Beer</a>
						</p>
<?php
	}
?>	
						<?php echo empty($establishment->address) ? '' : $establishment->address . '<br>'; ?>
						<?php echo empty($establishment->city) ? '' : '<a href="' . base_url() . 'establishment/city/' . $establishment->stateID . '/' . urlencode($establishment->city) . '">' . $establishment->city . '</a>, '; ?>
						<a href="<?php echo base_url(); ?>establishment/state/<?php echo $establishment->stateID; ?>"><?php echo $establishment->stateAbbr; ?></a>
						<?php echo empty($establishment->zip) ? '' : $establishment->zip; ?><br>
						<?php echo $this->phone->formatPhone($establishment->phone, true); ?>
						<?php echo empty($establishment->phone) ? '<br>' : '<br><br>'; ?>
						Categor<?php echo count($establishment->categories) > 1 ? 'ies' : 'y'; ?>:
<?php
	$cats = '';
	foreach ($establishment->categories as $cat => $name) {	
		$cats .= ucwords($name) . ', ';
	}
	echo rtrim($cats, ', ') . '<br><br>';

	if (!empty($beers)) {
		$average = 'N/A';
		if ($beer_average->totalBeers > 0) {
			$average = round(($beer_average->totalPoints / $beer_average->totalBeers), 1);
		}
?>
						<span class="green bold underline">Beer Review Stats:</span>
						<ul class="green no-bullets">
							<li>- <span class="bold"><?php echo $beer_distinct_count->totalBeers; ?></span> different beer<?php echo $beer_distinct_count->totalBeers != 1 ? 's' : ''; ?></li>
							<li>- drank <span class="bold"><?php echo $beer_average->totalBeers; ?></span> time<?php echo $beer_average->totalBeers != 1 ? 's' : ''; ?></li>
							<li>- <span class="bold"><?php echo (is_numeric($average) ? number_format($average, 1) : '0.0'); ?></span> average rating</li>
							<li>- <span class="bold">$<?php echo number_format($overalAverageCost->averagePrice, 2); ?></span> overall average cost per serving</li>
<?php
		if ($price_per_ounce > 0) {
?>
							<li>- <span class="bold">$<?php echo number_format($price_per_ounce['oz'], 2); ?></span> price per ounce</li>
<?php
		}
?>							
						</ul>
<?php						
	}
}
?>	
						<p>
							<a href="<?php echo base_url(); ?>establishment/info/rating/<?php echo $establishment->id; ?>/<?php echo $establishment->slug_establishment; ?>">Establishment Reviews</a> |
							<span>Beer Reviews</span>
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
if (!empty($beers)) {
	if (!empty($beer_active)) {
		$this->load->view('brewery/beer_grid.php', array('beer' => $beer_active, 'type' => 'active'));
	}

	if (!empty($beer_retired)) {
		$this->load->view('brewery/beer_grid.php', array('beer' => $beer_retired, 'type' => 'retired'));
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

if (!empty($highestRatedBreweries)) {
?>
					<div class="panel panel-default">
                        <div class="green bold panel-heading">Recent Reviews</div>
                        <ul class="list-group">
<?php
    foreach ($highestRatedBreweries as $highRating) {
?>
		                    <li class="list-group-item">
		                        <small>
		                        	<a href="<?php echo base_url(); ?>brewery/info/<?php echo $highRating->id; ?>/<?php echo $highRating->slug_establishment; ?>"><?php echo $highRating->name; ?></a>
			                        <br>
		                        	<span class="badge"><?php echo number_format($highRating->avgRating, 1); ?></span> for <span class="bold"><?php echo $highRating->beerTotal; ?></span> beer rating<?php echo $highRating->beerTotal == 1 ? '' : 's'; ?>
		                        </small>
		                    </li>
<?php
    }
?>    
                        </ul>
                    </div>
<?php
}

if (!empty($averageCostPerBreweryLow)) {
?>
					<div class="panel panel-default">
                        <div class="green bold panel-heading">Least Expensive Breweries</div>
                        <ul class="list-group">
<?php
    foreach ($averageCostPerBreweryLow as $ac) {
?>
		                    <li class="list-group-item">
		                        <small>
		                        	<a href="<?php echo base_url(); ?>brewery/info/<?php echo $ac->id; ?>/<?php echo $ac->slug_establishment; ?>"><?php echo $ac->name; ?></a>
		                        	<br>
		                        	<span class="bold">$<?php echo number_format($ac->averagePrice, 2); ?></span> for <span class="bold"><?php echo $ac->totalServings; ?></span> total serving<?php echo $ac->totalServings == 1 ? '' : 's'; ?>
		                        </small>
		                    </li>
<?php
    }
?>    
                        </ul>
                    </div>
<?php
}

if (!empty($averageCostPerBreweryHigh)) {
?>
					<div class="panel panel-default">
                        <div class="green bold panel-heading">Most Expensive Breweries</div>
                        <ul class="list-group">
<?php
    foreach ($averageCostPerBreweryHigh as $ac) {
?>
		                    <li class="list-group-item">
		                        <small>
		                        	<a href="<?php echo base_url(); ?>brewery/info/<?php echo $ac->id; ?>/<?php echo $ac->slug_establishment; ?>"><?php echo $ac->name; ?></a>
		                        	<br>
		                        	<span class="bold">$<?php echo number_format($ac->averagePrice, 2); ?></span> for <span class="bold"><?php echo $ac->totalServings; ?></span> total serving<?php echo $ac->totalServings == 1 ? '' : 's'; ?>
		                        </small>
		                    </li>
<?php
    }
?>    
                        </ul>
                    </div>
<?php
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
			'breweryName' => $brewery[0]['name']
			, 'breweryCity' => $brewery[0]['city']
			, 'breweryState' => $brewery[0]['stateFull']
			);
			$seo = getDynamicSEO($config);
*/
?>