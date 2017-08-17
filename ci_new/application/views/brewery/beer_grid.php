
	<table class="table table-striped table-bordered table-hover">
		<caption><?php echo ($type == 'active' ? 'Still Brewing' : 'Dried Up Suds'); ?> (<?php echo number_format(count(${'beer_' . $type})); ?>)</caption>
		<thead>
			<tr>
				<td>&nbsp;</td>
				<th>Beer</th>
				<th>Style</th>
				<th class="text-center"><span href="#" data-toggle="tooltip" data-placement="bottom" title="Number of ratings.">#Rev</span></th>
				<th class="text-center" data-toggle="tooltip" data-placement="bottom" title="Average rating out of 10.">RAvg</th>
				<th class="text-center" data-toggle="tooltip" data-placement="left" title="Percent that would have another.">H.A.</th>
				<th class="text-center" data-toggle="tooltip" data-placement="left" title="Average cost to have a serving.">Avg. Cost</th>
			</tr>
		</thead>
		<tbody>
<?php	
		foreach (${'beer_' . $type} as $record) {		
?>
			<tr>
				<td>
					<a href="<?php echo base_url(); ?>beer/review/<?php echo $record->id; ?>/<?php echo $record->slug_beer; ?>">
						<?php echo $record->image['source']; ?>
					</a>
				</td>
				<td><a href="<?php echo base_url(); ?>beer/review/<?php echo $record->id; ?>/<?php echo $record->slug_beer; ?>"><?php echo $record->beerName; ?></a></td>
				<td><a href="<?php echo base_url(); ?>beer/style/<?php echo $record->styleID; ?>"><?php echo $record->style; ?></a></td>
				<td class="text-center"><?php echo number_format($record->reviews); ?></td>
				<td class="text-center"><?php echo empty($record->averagereview) ? '0.0' : $record->averagereview; ?></td>
				<td class="text-center"><?php echo $record->avgHaveAnother; ?>%</td>
				<td><?php echo $this->pricing->getAveragePriceLingo($record); ?></td>
			</tr>
<?php
		}
?>
		</tbody>
	</table>