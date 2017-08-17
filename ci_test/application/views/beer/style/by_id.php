
	<h2 class="brown"><?php echo $style_info->origin; ?> <?php echo $style_info->styleType; ?>s: <?php echo $style_info->style; ?></h2>
	<p><?php echo $style_info->description; ?></p>
<?php
if ($totalBeers < 1) {
?>
	<div class="alert alert-warning" role="alert">No beers have been reviewed in the style.</div>
<?php	
}
else {
	$num_pages = $totalBeers / BEER_REVIEWS;

	$config['base_url'] = base_url() . 'beer/style/' . $id;
	$config['total_rows'] = $totalBeers;
	$config['per_page'] = BEER_REVIEWS;
	$config['uri_segment'] = 4;
	$config['num_links'] = 2;
	$config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination pagination-sm pull-right">';
	$config['full_tag_close'] = '</ul></nav>';
	$config['full_tag_open'] = '<ul class="pagination pagination-sm pull-right">';
	$config['full_tag_close'] = '</ul>';
	$config['prev_link'] = '&laquo;';
	$config['prev_tag_open'] = '<li>';
	$config['prev_tag_close'] = '</li>';
	$config['next_link'] = '&raquo;';
	$config['next_tag_open'] = '<li>';
	$config['next_tag_close'] = '</li>';
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	$config['cur_tag_open'] = '<li class="active">';
	$config['cur_tag_close'] = '</li>';
	$config['last_tag_open'] = '<li>';
	$config['last_tag_close'] = '</li>';
	$config['first_tag_open'] = '<li>';
	$config['first_tag_close'] = '</li>';
	$this->pagination->initialize($config);

	$pagination = '';
	if ($num_pages > 1)
	{
		$pagination = $this->pagination->create_links();
	}
?>
	<div class="row" style="margin-bottom: 20px;">
		<div class="col-xs-12 col-sm-6 col-md-6">
			<p class="green">
				<span class="bold"><?php echo number_format($totalBeers) . ' ' . $style_info->style; ?> Reviewed</span> 
			</p>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-6"><?php echo $pagination; ?></div>
	</div>

	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<td>&nbsp;</td>
				<th>Beer</th>
				<th>Brewery</th>
				<th class="text-center"><span href="#" data-toggle="tooltip" data-placement="bottom" title="Number of ratings.">#</span></th>
				<th class="text-center" data-toggle="tooltip" data-placement="bottom" title="Average rating out of 10.">Rate Avg.</th>
				<th class="text-center" data-toggle="tooltip" data-placement="left" title="Percent that would have another.">H.A.</th>
			</tr>
		</thead>
		<tbody>
<?php
	foreach ($records as $record) {
?>
			<tr>
				<td>
					<a href="<?php echo base_url(); ?>beer/review/<?php echo $record->beerID; ?>/<?php echo $record->slug_beer; ?>">
						<?php echo $record->image['source']; ?>
					</a>
				</td>
				<td><a href="<?php echo base_url(); ?>beer/review/<?php echo $record->beerID; ?>/<?php echo $record->slug_beer; ?>"><?php echo $record->beerName; ?></a></td>
				<td><a href="<?php echo base_url(); ?>brewery/info/<?php echo $record->establishmentID; ?>/<?php echo $record->slug_establishment; ?>"><?php echo $record->name; ?></a></td>
				<td class="text-center"><?php echo number_format($record->totalRatings); ?></td>
				<td class="text-center"><?php echo $record->avgRating; ?></td>
				<td class="text-center"><?php echo $record->avgHaveAnother; ?>%</td>
			</tr>
<?php		
	}
?>
		</tbody>
	</table>
<?php
	if ($num_pages > 1) {
?>
	<p><?php echo $pagination; ?></p>
<?php
	}
}

/*			
				// get configuration values for creating the seo
				$config = array(
					'style' => $array[0]['style']
					, 'description' => $array[0]['description']
					, 'origin' => $array[0]['origin']
					, 'styleType' => $array[0]['styleType']
				);
					// set the page information
				$seo = getDynamicSEO($config);
			

					foreach($array as $style) {						
						// COST BELOW

						//echo '<pre>'; print_r($style); echo '</pre>';
						// configuration for the image
						
						//IMAGE BELOW
						
						// check if the rating is set - will only happen for a beer
						// that has not been rated
						$avgRating = empty($style['avgRating']) ? '0.0' : $style['avgRating'];
						$output .= '
							<tr' . $class . '>
								<td class="td_first"><a href="' . base_url() . 'beer/review/' . $style['beerID'] . '"><img src="' . base_url() . 'page/createImage/' . $style['beerID'] . '/beer/mini" /></a></td>
								<td><a href="' . base_url() . 'beer/review/' . $style['beerID'] . '">' . $style['beerName'] . '</a></td>
								<td><a href="' . base_url() . 'brewery/info/' . $style['establishmentID'] . '">' . $style['name'] . '</a></td>
								<td class="center">' . number_format($style['totalRatings']) . '</td>
								<td class="center">' . $avgRating . '</td>
								<td class="center">' . $ha . '%</td>
								<!--<td class="td_last">' . $str . '</td>-->
							</tr>
						';
						// increment counter
						$cnt++;
					}
		
					$output .= '
							</table>
						</div>
						
						' . $pagination_bottom . '
					';					
				}
				
				// combine the arrays
				$str = $seo + array('str' => $output . '</div><br class="left" />', 'rightCol' => $rightCol) ;
			} else {
				// this is a precaution
				// should only be triggered if a value was entered
				// that is out of range for the database
				header('Location: ' . base_url());
				exit;
			}
		}
	}*/

/*foreach($avgCost as $cost) {
						 // there is a match so create the output
						 $serving = $cost['totalServings'] > 1 || $cost['totalServings'] < 1 ? ' servings' : ' serving';
						 $str .= '<p>$' . $cost['averagePrice'] . ', ' . $cost['totalServings'] . $serving . ', ' . $cost['package'] . 's</p>';
						 }*/


	/*$image = array(
						 'picture' => $style['picture']
						 , 'id' => $style['beerID']
						 , 'alt' => $style['name'] . ' - ' . $style['beerName']
						 , 'width' => 30
						 , 'height' => 70
						 );
						 // check if the image exists for this beer
						 $img = checkForImage($image, false, false);*/