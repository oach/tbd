	
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-sm-9 col-xs-12">
<?php
if (count($establishments) < 1) {
	$cat = !empty($category) ? ucwords($category->name) : 'Establishment';
?>
				<h2 class="brown"><?php echo $cat; ?> in <?php echo $stateInfo->stateFull; ?></h2>
				<div class="alert alert-warning" role="alert">There are no records for the state requested.</div>
<?php
}
else {
?>
				<h2 class="brown"><?php echo ucwords($category->name); ?> in <?php echo $stateInfo->stateFull; ?></h2>
				<table class="table table-striped table-hover table-condensed">
					<thead>
						<tr>
							<th>Name &#38; Contact Info.</th>	
							<th class="text-center">Reviews</th>					
							<th class="text-center">Rating</th>
<?php
    if ($categoryID == 1 || $categoryID == 4) {
?>
							<th class="text-center hidden-xs">Beers</th>
							<th class="text-center hidden-xs">Beer Rating</th>
<?php
    }
?>
						</tr>
					</thead>
					<tbody>
<?php
	foreach ($establishments as $establishment) {
?>
						<tr>
							<td>
								<a href="<?php echo base_url(); ?>establishment/info/rating/<?php echo $establishment->establishmentID; ?>/<?php echo $establishment->slug_establishment; ?>"><?php echo $establishment->name; ?></a><br>			
								<?php echo $establishment->address . (empty($establishment->address) ? '' : '<br>'); ?>
								<a href="<?php echo base_url(); ?>establishment/city/<?php echo $establishment->stateID; ?>/<?php echo urldecode($establishment->city); ?>"><?php echo $establishment->city; ?></a>, <a href="<?php echo base_url(); ?>establishment/state/<?php echo $establishment->stateID; ?>"><?php echo $establishment->stateAbbr; ?></a> <?php echo $establishment->zip; ?><br>
								<?php echo $this->phone->formatPhone($establishment->phone, true); ?>
							</td>
							<td class="text-center hidden-xs"><?php echo $establishment->totalRatings; ?></td>
							<td class="text-center hidden-xs"><?php echo $establishment->averageRating; ?></td>
<?php
        if ($categoryID == 1 || $categoryID == 4) {
?>
							<td class="text-center"><?php echo $establishment->beerTotalRatings; ?></td>
							<td class="text-center"><?php echo $establishment->beerAverageRating; ?></td>
<?php
        }
?>
						</tr>
<?php
	}
?>					
					</tbody>
				</table>
<?php
}
?>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-12">
    			<div class="side-info">
<?php
$this->load->view('establishment/more.php');
?>
    			</div>
    		</div>
		</div>
	</div>			
<!--
			$config = array(
				'breweryCity' => $establishments[0]['city']
				, 'breweryState' => $establishments[0]['stateFull']
				, 'seoType' => 'establishmentsByCity'
			);
			// set the page information
			$seo = getDynamicSEO($config);-->

