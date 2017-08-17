<?php
$similar = $this->EstablishmentModel->getEstablishmentsRatingUserID($rating->userID);

if (count($similar) > 0)
{
?>
	<div class="similarBeers">
		<table class="table table-bordered table-condensed table-striped">
			<caption>Visited Establishments</caption>
			<thead>
				<tr>
					<th>Place</th>
					<th>Rating</th>
				</tr>
			</thead>
			<tbody>
<?php
	foreach ($similar as $key)
	{
?>		
				<tr>
					<td><a href="<?php echo base_url(); ?>establishment/info/rating/<?php echo $key->establishmentID; ?>/<?php echo $key->slug_establishment; ?>"><?php echo $key->name; ?></a></td>
					<td class="text-center"><?php echo number_format($key->rating, 1); ?></td>
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