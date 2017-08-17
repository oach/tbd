
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-sm-9 col-xs-12">
				<h2 class="brown">Brewery Hop to <?php echo $breweryhop->name; ?></h2>
				<p>on <?php echo $breweryhop->hopDate; ?></p>			
				<div id="breweryhop"><?php echo $breweryhop->article; ?></div>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-12">
    			<div class="side-info">
<?php
$this->load->view('page/brewery_hop_right.php');
?>
    			</div>
    		</div>
		</div>
	</div>
		
<?php
/*
$config = array(
	'breweryName' => $breweryhop['name']
	, 'breweryCity' => $breweryhop['city']
	, 'breweryState' => $breweryhop['stateFull']
);
$seo = getDynamicSEO($config);
*/
?>
