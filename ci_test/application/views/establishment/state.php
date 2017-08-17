
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-sm-8 col-xs-12">
<?php
$establishment_count = count($establishments);
if ($establishment_count < 1) {
?>
				<h2 class="hop brown">Establishments</h2>
				<div class="alert alert-warning" role="alert">There are no records for the state requested.</div>
<?php	
}
else {
?>			
				<h2 class="hop brown">Establishments in <?php echo $stateInfo->stateFull; ?></h2>
				
				<h3 class="green">By City</h3>
				<div class="row">
<?php
	$cnt = 0;
	$div = ($establishment_count % 2 == 0) ? ($establishment_count / 2) : ($establishment_count / 2) + 1;
	foreach ($establishments as $establishment) {
		$mod = $cnt % $div;
		if ($cnt > 0 && $mod == 0) {
?>					
					</div><div class="col-xs-12 col-sm-6 col-md-6">
<?php					
		}
		
		if ($cnt == 0) {
?>
					<div class="col-xs-12 col-sm-6 col-md-6">
<?php					
		}
?>
						<a href="<?php echo base_url(); ?>establishment/city/<?php echo $stateInfo->id; ?>/<?php echo urlencode($establishment->city); ?>"><?php echo $establishment->city; ?> <span class="badge"><?php echo number_format($establishment->totalPerCity); ?></span></a><br>
<?php						
		$cnt++;
	}
?>
					</div>				
				</div>

				<h3 class="green">By Category</h3>
				<div class="row">
<?php
	$category_count = count($categories);
	if ($category_count > 0) {
		$cnt = 0;
		$div = ($category_count % 2 == 0) ? ($category_count / 2) : ($category_count / 2) + 1;

		foreach ($categories as $category) {
			$mod = $cnt % $div;
			if ($cnt > 0 && $mod == 0) {
?>
					</div><div class="col-xs-12 col-sm-6 col-md-6">
<?php					
			}
			
			if ($cnt == 0) {
?>
					<div class="col-xs-12 col-sm-6 col-md-6">
<?php				
			}
			
			if ($category->totalPerCategory > 0) {
?>
					<a href="<?php echo base_url(); ?>establishment/info/category/<?php echo $category->id; ?>/<?php echo $stateInfo->id; ?>"><?php echo $category->name; ?> <span class="badge"><?php echo number_format($category->totalPerCategory); ?></span></a><br>
<?php					
			}
			else {
?>				
					<?php echo $category->name; ?> <span class="badge"><?php echo number_format($category->totalPerCategory); ?></span><br>
<?php				
			}
			$cnt++;
		}
	}
?>
					</div>
<?php
}
?>
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
    			<div class="side-info">
<?php
$this->load->view('establishment/more.php');
?>
    			</div>
    		</div>
		</div>
	</div>			
			
		<!--	// get configuration values for creating the seo
			$config = array(
				'breweryState' => $establishment['stateFull']
				, 'seoType' => 'establishmentsByState'
			);
			// set the page information
			$seo = getDynamicSEO($config);-->
