
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-sm-9 col-xs-12">
<?php
if (count($establishments) < 1) {
?>
				<h2 class="hop brown">Establishments</h2>
				<div class="alert alert-warning" role="alert">There are no records for the state requested.</div>
<?php	
}
else {
?>			
				<h2 class="hop brown">Establishments in <?php echo $city . ', ' . $stateInfo->stateFull; ?></h2>
				<table class="table table-striped table-hover table-condensed">
					<thead>
						<tr>
							<th>Name</th>						
							<th>Contact Info.</th>
							<th class="text-center hidden-xs">Rating</th>
							<th class="text-center hidden-xs">Reviews</th>
							<th class="hidden-xs">Category</th>
						</tr>
					</thead>
					<tbody>
<?php
	foreach ($establishments as $establishment) {
		$category = '';
		if (is_array($establishment->categories) && count($establishment->categories) > 0) {
			foreach ($establishment->categories as $id => $cat) {
				$category .= $cat . '<br>';
			}
			$category = rtrim($category, '<br>');
		}
?>
						<tr>
							<td>
								<a href="<?php echo base_url(); ?>establishment/info/rating/<?php echo $establishment->establishmentID; ?>/<?php echo $establishment->slug_establishment; ?>">
									<?php echo $establishment->name; ?>
								</a>
							</td>
							<td>
								<?php echo $establishment->address . (empty($establishment->address) ? '' : '<br>'); ?>
								<a href="<?php echo base_url(); ?>establishment/city/<?php echo $establishment->stateID; ?>/<?php echo urlencode($establishment->city); ?>"><?php echo $establishment->city; ?></a>, <a href="<?php echo base_url(); ?>establishment/state/<?php echo $establishment->stateID; ?>"><?php echo $establishment->stateAbbr; ?></a> <?php echo $establishment->zip; ?><br>
								<?php echo $this->phone->formatPhone($establishment->phone, true); ?>
							</td>
							<td class="text-center hidden-xs"><?php echo ($establishment->totalRatings > 0) ? number_format($establishment->averageRating, 1) : 'N/A'; ?></td>
							<td class="text-center hidden-xs"><?php echo ($establishment->totalRatings > 0) ? number_format($establishment->totalRatings, 1) : 'N/A'; ?></td>
							<td class="hidden-xs"><?php echo $category; ?></td>
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

			
<!--			$config = array(
				'breweryCity' => $establishments[0]['city']
				, 'breweryState' => $establishments[0]['stateFull']
				, 'seoType' => 'establishmentsByCity'
			);
			// set the page information
			$seo = getDynamicSEO($config);
-->
