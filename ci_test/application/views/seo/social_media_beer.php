<?php
if (SHOW_TWITTER_BEER_REVIEWS || SHOW_FACEBOOK_BEER_REVIEWS) {
?>
	<div class="panel panel-default">
	    <div class="green bold panel-heading">Spread the Word</div>
	    <ul class="list-group">
<?php
	if (SHOW_TWITTER_BEER_REVIEWS) {
		$twitter = !empty($beer_info->twitter) ? '@' . $beer_info->twitter : $beer_info->beerName;
?>
    		<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
			<li class="list-group-item"> <a 
                href="http://twitter.com/share" 
                class="twitter-share-button" 
                data-url="<?php echo base_url(); ?>beer/review/<?php echo $beer_info->id; ?>/<?php echo $beer_info->slug_beer; ?>"
                data-via="twobeerdudes"
                data-text="<?php echo $beer_info->beerName; ?> by <?php echo $twitter; ?>"
                data-count="horizontal"
            >Tweet</a></li>		            
<?php
	} 

	if (SHOW_FACEBOOK_BEER_REVIEWS) {
?>
	        <li id="fb-root"></li>
			<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
	        <fb:like href="<?php echo base_url(); ?>beer/review/<?php echo $beer_info->id; ?>" layout="button-count" show_faces="false" width="270" font="arial" style="margin-bottom: 0.4em; height: 24px;"></fb:like>
<?php
	}
?>
		</ul>
    </div>
<?php
}
?>
