
   <div class="container">
        <div class="row">
            <div class="col-sx-12 col-sm-9 col-md-9">
<?php
if (isset($beer_reviews)) {
?>
				<div class="row">
                    <div class="col-xs-12 col-md-8">
                        <h2 class="keg brown">Recent Beer Reviews</h2>
                    </div>
                    <div class="col-xs-12 col-md-4">
    				    <p class="pull-right" style="margin-top: 30px;"><a class="brown" href="<?php echo base_url(); ?>beer/reviews">View All Reviews</a></p>
                    </div>
                </div>
				
                <table class="table table-bordered table-hover table-striped">
                    <thead>
    					<tr>
    						<th>Beer</th>
    						<th>Brewery</th>
    						<th class="hidden-xs">Member</th>
    						<th class="hidden-xs">Style</th>
    						<th>Rating</th>
    					</tr>
                    </thead>
                    <tbody>
<?php				
	foreach ($beer_reviews as $item) {
?>
    					<tr>
    						<td><a class="green" href="<?php echo base_url(); ?>beer/review/<?php echo $item->beerID . '/' . $item->slug_beer; ?>"><?php echo $item->beerName; ?></a></td>
    						<td><a class="lightblue" href="<?php echo base_url(); ?>brewery/info/<?php echo $item->establishmentID . '/' . $item->slug_establishment; ?>"><?php echo $item->name; ?></a></td>
    						<td class="hidden-xs"><a class="mediumgray" href="<?php echo base_url(); ?>user/profile/<?php echo $item->userID; ?>"><?php echo $item->username; ?></a></td>
    						<td class="hidden-xs"><a class="lightblue" href="<?php echo base_url(); ?>beer/style/<?php echo $item->styleID; ?>"><?php echo $item->style; ?></a></td>
    						<td><span class="big-text label<?php echo $this->rating->getRatingsLabelClass($item->rating); ?>"><?php echo number_format(round($item->rating, 1), 1); ?></span></td>
    					</tr>	
<?php
	}
?>
				    </tbody>
                </table>
<?php
}

if (isset($brewery_hop)) {
	foreach ($brewery_hop as $hop) {
        $this->load->view('page/brewery_hop.php', array('hop' => $hop));
	}
}
?>
				<div id="blogPosts"></div>
			</div>

            <div class="cols-xs-12 col-sm-3 col-md-3">
                <div class="side-info">

                    <div id="twitter_div">
                        <a class="twitter-timeline green" href="https://twitter.com/twobeerdudes" data-widget-id="568157744016793600">Tweets by @twobeerdudes</a>                   
                    </div>
<?php
if (isset($season)) {
?>
                    <div class="panel panel-default">
                        <div class="green bold panel-heading <?php echo $season[0]->className; ?>">Seasonal Indicator</div>
                        
                    
<?php
    foreach ($season as $item) {
?>
                        <ul class="list-group">
                            <li class="list-group-item"><?php echo $item->season; ?> (<?php echo $this->date->get_month_names($item->monthrange); ?>)</li>
                            <li class="list-group-item"><?php echo $item->beerstyles; ?></li>
                        </ul>
<?php                    
    }
?>
                    </div>
<?php
}
?>
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Keep Up To Date</div>
                        <ul class="list-group">
                            <li class="list-group-item"><a href="http://www.facebook.com/twobeerdudes" target="_blank" rel="nofollow"><img src="<?php echo base_url(); ?>images/facebook.jpg" alt="two beer dudes facebook icon"></a>
                        <a href="http://twitter.com/twobeerdudes" target="_blank" rel="nofollow"><img src="<?php echo base_url(); ?>images/twitter.jpg" alt="two beer dudes twitter icon"></a></li>
                        </ul>
                    </div>
                </div>        
            </div>
        </div>
    </div>
    