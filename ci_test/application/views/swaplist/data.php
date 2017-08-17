<?php
if (count($swaps) < 1) {
	$error = '<p>There are no beers in your Swap ' . ucfirst($swap_type) . ' list.</p>';
	if ($id != $user_info['id']) {
		$error = '<p><a href="' . base_url() . 'user/profile/' . $id . '">' . $user_name . '</a> does not have beers on his/her Swap ' . ucfirst($swap_type) . ' list.</p>';
	}              
?>
				<script>
				$(function() {
					$('#alert').removeClass().addClass('alert alert-warning').html('<p><?php echo $error; ?></p>');
				});					
				</script>
<?php
}
else {	
?>
				<script>
				$(function() {
					$('#alert').addClass('hide');
				});					
				</script>
				<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
		                    <tr>
		                        <th>&nbsp;</th>
		                        <th>Beer</th>
		                        <th># Ins</th>
		                        <th># Outs</th>
		                        <th class="hidden-xs">Date</th>
<?php
	if ($id == $user_info['id']) {
?>
								<th>&nbsp;</th>
<?php					
	}
?>
							</tr>
						</thead>
						<tbody>
<?php
	$cnt = 1;
	foreach ($swaps as $item) {
		$swapOutCount = $this->SwapModel->numberSwapOutsByBeerID($item->beerID);
        $swapInCount = $this->SwapModel->numberSwapInsByBeerID($item->beerID);
?>

		                    <tr>
		                        <td><?php echo $cnt; ?>.</td>
		                        <td>
		                        	<a href="<?php echo base_url(); ?>beer/review/<?php echo $item->beerID; ?>"><?php echo $item->beerName; ?></a><br>
		                        	<a href="<?php echo base_url(); ?>brewery/info/<?php echo $item->establishmentID; ?>"><?php echo $item->name; ?></a>
		                        </td>
		                        <td><a href="<?php echo base_url(); ?>beer/swaps/ins/<?php echo $item->beerID; ?>"><?php echo $swapInCount; ?></a></td>
		                        <td><a href="<?php echo base_url(); ?>beer/swaps/outs/<?php echo $item->beerID; ?>"><?php echo $swapOutCount; ?></a></td>
		                        <td class="hidden-xs"><?php echo $item->swapDate; ?></td>
<?php
		if ($id == $user_info['id']) {
?>
								<td><a href="#" class="btn btn-danger btn-xs swap-remove" data-id="<?php echo $item->beerID; ?>" data-type="<?php echo $swap_type; ?>">remove</a></td>
<?php
		}
?>                    
							</tr>
						</tbody>
<?php
		$cnt++;
	}
?>
					</table>
				</div>
<?php
}
?>
				<script>
				var swaps = <?php echo count($swaps); ?>;
				var s = swaps == 1 ? '' : 's';
				</script>