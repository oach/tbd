
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6">
	        <img id="brewery_hop_pic" class="img-responsive" src="<?php echo base_url(); ?>images/<?php echo $hop->brewerypic; ?>" alt="<?php echo $hop->name; ?>">
	    </div>
		<div class="col-xs-12 col-sm-12 col-md-6">
			<h2><a class="brown" href="<?php echo base_url(); ?>brewery/hop/<?php echo $hop->id; ?>"><?php echo $hop->name; ?></a></h2>
			<p class="mediumgray">Author: <?php echo $hop->author; ?></p>
			<p><?php echo $hop->shorttext; ?></p>
			<p><a href="<?php echo base_url(); ?>brewery/hop/<?php echo $hop->id; ?>">Read More <span class="glyphicon glyphicon-play-circle mediumgray" aria-hidden="true"></span></a></p>
		</div>
	</div>