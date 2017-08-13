<?php
$similar = $this->BeerModel->getBeerRatingByStyleAndUserID($beer->styleID, $beer->userID, $beer->id);

if (count($similar) > 0)
{
?>
	<div class="similarBeers">
		<table class="table table-bordered table-condensed table-striped">
			<caption>Similar Beers Tasted</caption>
			<thead>
				<tr>
					<th>Beer</th>
					<th>Rating</th>
				</tr>
			</thead>
			<tbody>
<?php
	foreach ($similar as $key)
	{
?>		
				<tr>
					<td><a href="<?php echo base_url(); ?>beer/review/<?php echo $key->id; ?>"><?php echo $key->beerName; ?></a></td>
					<td class="text-center"><?php echo $key->rating; ?></td>
				</tr>
<?php
        }
?>
			</tbody>
		</table>
	</div>        
<?php
}
?>
			
