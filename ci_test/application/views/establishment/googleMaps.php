
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-sm-8 col-xs-12">
<?php
if (empty($establishment)) {
?>			
				<h2 class="brown">Map</h2>
				<div class="alert alert-warning" role="alert">There was an issue displaying the map.</div>
<?php
}
else {
?>				
				<h2 class="brown">Map for <?php echo $establishment->name; ?></h2>
				<p>
					Back to 
					<a href="<?php echo base_url(); ?>brewery/info/<?php echo $establishment->establishmentID; ?>/<?php echo $establishment->slug_establishment; ?>">
						<?php echo $establishment->name; ?>
					</a>
				</p>
				<div style="overflow: hidden; position: relative; height: 0; padding-bottom: 56.25%">
				<div id="map" style="left: 0; top: 0; height: 100%; width: 100%; position: absolute;"></div>
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
    			<div class="side-info">
    				<div class="panel panel-default">
    					<div class="green bold panel-heading">
    						Establishments Within <?php echo RADIUS_SEARCH; ?> Miles
<?php
	if (!empty($closeBy)) {
?>
							<span class="label label-default"><?php echo count($closeBy); ?></span>
<?php	
	}
?>    						
    					</div>
                        <ul class="list-group">
<?php
}

if (empty($closeBy)) {
?>
							<li class="list-group-item">No results</li>
<?php
}
else {
	foreach ($closeBy as $store) {
?>							
		                    <li class="list-group-item">
		                    	<small>
		                    		<a href="<?php echo base_url(); ?>brewery/info/<?php echo $store->establishmentID; ?>/<?php echo $store->slug_establishment; ?>"><?php echo $store->name; ?></a> - <span class="bold"><?php echo number_format($store->distance, 2); ?></span> mile<?php echo $store->distance == 1.00 ? '' : 's'; ?>
		                    		<br>
		                    		<?php echo $store->address; ?>, <a href="<?php echo base_url(); ?>establishment/city/<?php echo $store->stateID; ?>/<?php echo urlencode($store->city); ?>"><?php echo $store->city; ?></a>, <a href="<?php echo base_url(); ?>establishment/state/<?php echo $store->stateID; ?>"><?php echo $store->stateAbbr; ?></a> <?php echo $store->zip; ?>
		                    	</small>
		                    </li>
<?php
    }
}
?>    
                        </ul>
                    </div>
    			</div>
    		</div>
		</div>
	</div>

	<script>
	var establishment_latitude = <?php echo $establishment->latitude; ?>;
	var establishment_longitude = <?php echo $establishment->longitude; ?>;
	var establishment_locations = [		    	
<?php
$i = 2;  
foreach($closeBy as $location) {
?>
		['<div id="content"><div id="siteNotice"></div><h2 class="brown"><a href="<?php echo base_url(); ?>/establishment/info/rating/<?php echo $location->establishmentID; ?>" class="brown"><?php echo htmlspecialchars($location->name, ENT_QUOTES); ?></a></h2><div id="bodyContent"><p><?php echo $location->address; ?><br><a href="<?php echo base_url(); ?>establishment/city/<?php echo $location->stateID; ?>/<?php echo urlencode($location->city); ?>"><?php echo htmlspecialchars($location->city, ENT_QUOTES); ?></a>, <a href="<?php echo base_url(); ?>establishment/state/<?php echo $location->stateID; ?>"><?php echo $location->stateAbbr; ?></a> <?php echo $location->zip; ?><br><?php echo $this->phone->formatPhone($location->phone); ?><br><a href="<?php echo $location->url; ?>" target="_blank"><?php echo $location->url; ?></a></p></div></div>', '<?php echo $location->latitude; ?>', '<?php echo $location->longitude; ?>', '<?php echo ($i == 1 ? 100 : $i); ?>', '<?php echo addslashes(html_entity_decode($location->name, ENT_QUOTES)); ?>'],
<?php
	$i++;
}
?>
		['<div id="content"><div id="siteNotice"></div><h2 class="brown"><a href="<?php echo base_url(); ?>/establishment/info/rating/<?php echo $establishment->establishmentID; ?>" class="brown"><?php echo htmlspecialchars($establishment->name, ENT_QUOTES); ?></a></h2><div id="bodyContent"><p><?php echo $establishment->address; ?><br><a href="<?php echo base_url(); ?>establishment/city/<?php echo $establishment->stateID; ?>/<?php echo urlencode($establishment->city); ?>"><?php echo htmlspecialchars($establishment->city, ENT_QUOTES); ?></a>, <a href="<?php echo base_url(); ?>establishment/state/<?php echo $establishment->stateID; ?>"><?php echo $establishment->stateAbbr; ?></a> <?php echo $establishment->zip; ?><br><?php echo $this->phone->formatPhone($establishment->phone); ?><br><a href="<?php echo $establishment->url; ?>" target="_blank"><?php echo $establishment->url; ?></a></p></div></div>', '<?php echo $establishment->latitude; ?>', '<?php echo $establishment->longitude; ?>', '1', '<?php echo addslashes(html_entity_decode($establishment->name, ENT_QUOTES)); ?>']
    ];
	</script>
