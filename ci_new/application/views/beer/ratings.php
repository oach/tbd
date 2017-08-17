
	<div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-9">
                <h2 class="brown"><?php echo $type == 'high' ? 'Highest' : 'Lowest'; ?> Rated American Craft Beers</h2>
<?php
if (count($beers) > 0) {
?>
                <table class="table table-striped table-bordered table-hover table-condensed">
                	<thead>
						<tr>
							<th>&nbsp;</th>
							<th>American Craft Beer</th>
							<th class="text-center">Rating</th>
							<th class="text-center">Reviews</th>
						</tr>
					</thead>
					<tbody>
<?php
    foreach ($beers as $item) {
?>
                        <tr>
							<!--<td style="vertical-align: middle; width: 40px;">-->
                            <td class="vertical-middle width-40">
								<a href="<?php echo base_url(); ?>beer/review/<?php echo $item->id; ?>/<?php echo $item->slug_beer; ?>">
									<?php echo $item->image['source']; ?>
								</a>
							</td>
							<td>
								<ul class="formatted-list">
                                    <li><a href="<?php echo base_url(); ?>beer/review/<?php echo $item->id; ?>/<?php echo $item->slug_beer; ?>"><?php echo $item->beerName; ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>brewery/info/<?php echo $item->establishmentID; ?>/<?php echo $item->slug_establishment; ?>"><?php echo $item->name; ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>beer/style/<?php echo $item->styleID; ?>"><?php echo $item->style; ?></a></li>
                                </ul>
							</td>
							<td class="text-center"><span class="label <?php echo $this->rating->getRatingsLabelClass($item->averagerating); ?>"><?php echo number_format($item->averagerating, 1); ?></span></td>
							<td class="text-center"><?php echo number_format($item->totalratings, 0, '.', ','); ?></td>
						</tr>
<?php
    }
?>
                    </tbody>
                </table>
<?php
}
else {
?>
                <div class="alert alert-warning" role="alert">There are no beer matching the searched criteria.</div>
<?php    
}
?>                
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-3">
                <div class="side-info">
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Other Lists</div>
                        <ul class="list-group">
                            <li class="list-group-item"><a href="<?php echo base_url(); ?>beer/ratings/<?php echo $type == 'high' ? 'low' : 'high'; ?>"> <?php echo $type == 'high' ? 'Lowest' : 'Highest'; ?> rated beers</a></li>
                        </ul>
                    </div>
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Top 50</div>
                        <ul class="list-group">
                            <li class="list-group-item">More functionality needed.  Let us know: <a href="<?php echo base_url(); ?>page/contactUs">contact us</a>.</li>
                        </ul>
                    </div>
<?php
if (count($bestStyles) > 0) {
?>
					<div class="panel panel-default">
                        <div class="green bold panel-heading"><?php echo $type == 'high' ? 'Highest' : 'Lowest'; ?> Rated Styles</div>
                        <ul class="list-group">
<?php
	foreach ($bestStyles as $item) {
?>                        
                            <li class="list-group-item">
                            	<small>
                            		<span class="label <?php echo $this->rating->getRatingsLabelClass($item->avgRating); ?>"><?php echo $item->avgRating; ?></span>
                            		<a href="<?php echo base_url(); ?>beer/style/<?php echo $item->id; ?>"><?php echo $item->style; ?></a><br>
                            		<?php echo number_format($item->totalBeers, 0, '.', ','); ?> beer<?php echo ($item->totalBeers == 1 ? '' : 's'); ?>, rated <?php echo number_format($item->totalRatings, 0, '.', ','); ?> time<?php echo ($item->totalRatings == 1 ? '' : 's'); ?>
								</small>
                            </li>
<?php 
	}
?>                           
                        </ul>
                    </div>
<?php	
}
?>                    
                </div>
            </div>
        </div>
    </div>

<?php
/*
<li>
	<div class="bottleCap"><p>' . $item['avgRating'] . '</p></div>
	<div class="rightSimilar">
		<p><a href="' . base_url() . 'beer/style/' . $item['id'] . '">' . $item['style'] . '</a></p>
		<p class="rightBreweryLink">' . number_format($item['totalBeers'], 0, '.', ',') . ' beer' . $brs . ', rated ' . number_format($item['totalRatings'], 0, '.', ',') . ' time' . $times . '</a></p>
	</div>
	<br class="left" />
</li>
*/