<?php
if (SHOW_TWITTER_ESTABLISHMENT || SHOW_FACEBOOK_ESTABLISHMENT) {
?>
	<div class="panel panel-default">
	    <div class="green bold panel-heading">Spread the Word</div>
	    <ul class="list-group">
<?php
	if (SHOW_TWITTER_ESTABLISHMENT) {
		$twitter = !empty($establishment->twitter) ? '@' . $establishment->twitter : $establishment->name;
?>
    		<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
			<li class="list-group-item"> <a 
                href="http://twitter.com/share" 
                class="twitter-share-button" 
                data-url="<?php echo base_url(); ?>brewery/info/<?php echo $establishment->id; ?>/<?php echo $establishment->slug_establishment; ?>"
                data-via="twobeerdudes"
                data-text="<?php echo $establishment->name; ?> by <?php echo $twitter; ?>"
                data-count="horizontal"
            >Tweet</a>		            
<?php
	} 

	if(SHOW_FACEBOOK_ESTABLISHMENT) {
?>
	        <!--<li class="list-group-item" id="fb-root"></li>-->
	        <span class="list-group-item" id="fb-root"></span>
			<!--<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>-->
			<script src="http://connect.facebook.net/en_US/all.js#appId=202679286436515&amp;xfbml=1"></script>
	        <fb:like href="<?php echo base_url(); ?>brewery/info/<?php echo $establishment->id; ?>/<?php echo $establishment->slug_establishment; ?>" layout="button-count" show_faces="false" width="270" font="arial" style="margin-bottom: 0.4em; height: 24px;"></fb:like>
<?php
	}
?>
			</li>
		</ul>
    </div>	    
<?php
}
?>
