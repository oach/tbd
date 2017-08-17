
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-sm-9 col-xs-12">
				<h2 class="brown">Brewery Hops</h2>
				<p>Our brewery hops are here for all to experience breweries, beer establishments, and festivals from our point
					of view.  We will try to go somewhere once a month as our schedules allow.  Most of travels will be to the 
					midwest, centralizing around Chicagoland.</p>
				<?php $this->load->view('page/brewery_hop.php', array('hop' => $hop[0])); ?>
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
