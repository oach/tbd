
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-9">
				<h2 class="brown">Recent Beer Reviews</h2>

				<div class="row" style="margin-bottom: 20px;">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<p class="green">
							<span class="bold"><?php echo number_format($totalNumberBeers); ?></span> Beers,
							<span class="bold"><?php echo number_format($totalResults); ?></span> Reviews
						</p>
					</div>
<?php
if (count($items > 0)) {
	$num_pages = $totalResults / BEER_REVIEWS;

	$config['base_url'] = base_url() . 'beer/reviews/pgn';
	$config['total_rows'] = $totalResults;
	$config['per_page'] = BEER_REVIEWS;
	$config['uri_segment'] = 4;
	$config['num_links'] = 2;
	$config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination pagination-sm pull-xs-left pull-sm-left pull-md-right pull-lg-right">';
	$config['full_tag_close'] = '</ul></nav>';
	$config['full_tag_open'] = '<ul class="pagination pagination-sm pull-xs-left pull-sm-left pull-md-right pull-lg-right">';
	$config['full_tag_close'] = '</ul>';
	$config['prev_link'] = '&laquo;';
	$config['prev_tag_open'] = '<li>';
	$config['prev_tag_close'] = '</li>';
	$config['next_link'] = '&raquo;';
	$config['next_tag_open'] = '<li>';
	$config['next_tag_close'] = '</li>';
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	$config['cur_tag_open'] = '<li class="active">';
	$config['cur_tag_close'] = '</li>';
	$config['last_tag_open'] = '<li>';
	$config['last_tag_close'] = '</li>';
	$config['first_tag_open'] = '<li>';
	$config['first_tag_close'] = '</li>';
	$this->pagination->initialize($config);

	if( $num_pages > 1) {
		$pagination = $this->pagination->create_links();
	}
?>
					<div class="col-xs-12 col-sm-6 col-md-6"><?php echo $pagination; ?></div>
				</div>
<?php
	$str = '';
	foreach ($items as $item) {
		$ratingInfo = $this->BeerModel->getBeerRating($item->beerID);
		$ratingAverage =  number_format(round($ratingInfo->averagerating, 1), 1);
		$ratingTotalTimes = $ratingInfo->timesrated;
		$ratingTotalTimes .= $ratingTotalTimes == 1 ? ' dude' : ' dudes';

		$percentHaveAnother = $this->BeerModel->getHaveAnotherPercent($item->beerID);
		$thmb = $percentHaveAnother >= 50 ? 'yes' : 'no';
		// there is a match so create the output
		$haveMore = '<img src="' . base_url() . 'images/haveanother_' . $thmb . '25.jpg" width="25" height="25" title="have another ' . $item->beerName . ' by ' . $item->name . '" alt="have another ' . $item->beerName . ' by ' . $item->name . '" /> ' . $percentHaveAnother . '%';
		
		// determine if they would have another
		$thumb = $item->haveAnother == 1 ? 'yes' : 'no';
		$have = '<img src="' . base_url() . 'images/haveanother_' . $thumb . '25.jpg" width="25" height="25" title="have another ' . $item->beerName . ' by ' . $item->name . '" alt="have another ' . $item->beerName . ' by ' . $item->name . '">';
?>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="hidden-xs col-sm-2 col-md-2"
						style="background-image: url(<?php echo base_url(); ?>page/createImage/<?php echo $item->beerID; ?>/beer); background-position: left 20px top; background-repeat: no-repeat; min-height: 175px;"></div>
					<div class="col-xs-12 col-sm-10 col-md-10">
						<p><a class="bold" href="<?php echo base_url(); ?>beer/review/<?php echo $item->beerID; ?>/<?php echo $item->slug_beer; ?>"><?php echo $item->beerName; ?></a></p>
						<p><a href="<?php echo base_url(); ?>brewery/info/<?php echo $item->establishmentID; ?>/<?php echo $item->slug_establishment; ?>"><?php echo $item->name; ?></a></p>
						<p><a href="<?php echo base_url(); ?>beer/style/<?php echo $item->styleID; ?>"><?php echo $item->style; ?></a></p>
						<p>
							<span class="score"><?php echo number_format(round($item->rating, 1), 1); ?></span>/10 by 
							<a href="<?php echo base_url(); ?>user/profile/<?php echo $item->userID; ?>"><?php echo $item->username; ?></a> 
							<span class="ha"><?php echo $have; ?></span>
						</p>
<?php
		if ($item->shortrating == '1') {
			$ratingCalc = number_format((($item->aroma * (PERCENT_AROMA / 100)) + 
				($item->taste * (PERCENT_TASTE / 100)) + 
				($item->look * (PERCENT_LOOK / 100)) + 
				($item->drinkability * (PERCENT_DRINKABILITY / 100))), 1);
?>
						<p class="bold">Short Rating</p>
						<p>Aroma: <span class="bold"><?php echo $item->aroma; ?></span> (<?php echo PERCENT_AROMA; ?>%)</p>
						<p>Taste: <span class="bold"><?php echo $item->taste; ?></span> (<?php echo PERCENT_TASTE; ?>%)</p>
						<p>Look: <span class="bold"><?php echo $item->look; ?></span> (<?php echo PERCENT_LOOK; ?>%)</p>
						<p>Drinkability: <span class="bold"><?php echo $item->drinkability; ?></span> (<?php echo PERCENT_DRINKABILITY; ?>%)</p>
						<p><span class="bold requied">Overall: <?php echo $ratingCalc; ?></span></p>
<?php
		}
		else {
?>			
						<p><?php echo substr($item->comments, 0, 500); ?>...<a href="<?php echo base_url(); ?>beer/review/<?php echo $item->beerID; ?>">read more</a></p>
<?php
		}
?>
						<p class="ratingInfo">
							Overall Rating: <span class="green"><?php echo number_format($ratingAverage, 1); ?></span>/10 by 
							<span><?php echo $ratingTotalTimes; ?></span> <span class="ha"><?php echo $haveMore; ?></span>
						</p>
					</div>
				</div>
			</div>
		</div>
<?php		
	}
}
?>
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12"><?php echo $pagination; ?></div>
				</div>
			</div>
            
            <div class="col-xs-12 col-sm-12 col-md-3">
    			<div class="side-info">
    				<div class="panel panel-default">
				        <div class="green bold panel-heading">Beer Review Thoughts</div>
				        <ul class="list-group">
							<li class="list-group-item">Have fun with your reviews, let out your personality while keeping it clean for all members and visitors alike.</li>
				        </ul>
				    </div>
				    <div class="panel panel-default">
				        <div class="green bold panel-heading">Malted Mail</div>
				        <ul class="list-group">
							<li class="list-group-item"><a href="<?php echo base_url(); ?>beer/style">Beer Styles</a></li>
				            <li class="list-group-item"><a href="<?php echo base_url(); ?>beer/srm">Beer Colors</a></li>
				            <li class="list-group-item"><a href="<?php echo base_url(); ?>beer/ratingSystem">Beer Rating System</a></li>
				        </ul>
				    </div>
    			</div>
    		</div>
        </div>
	</div>