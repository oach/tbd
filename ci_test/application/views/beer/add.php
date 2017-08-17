
	<div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-9">
<?php
if ($action == 'no_sell') {
	$tmp = !$establishment ? ' ' : ' (<a href="' . base_url() . 'establishment/info/rating/' . $establishment->establishmentID . '/' . $establishment->slug_establishment . '">' . $establishment->name . '</a>) ';
?>
				<h2 class="brown">Add A Beer</h2>
				<div class="alert alert-danger" role="alert">According to our records, the establishment<?php echo $tmp; ?>you have chosen doesn&#39;t brew/sell their own beer.</div>
<?php				
}
elseif ($action == 'max_reviews') {
?>
				<h2 class="brown">Add A Beer</h2>
				<div class="alert alert-danger" role="alert">You haven\'t reviewed enough beers (you need <?php echo MIN_REVIEW_COUNT; ?> reviews) to add a new beer.</div>
<?php				
}
elseif ($action == 'show_form') {
?>
				<h2 class="brown">Add A Beer For <?php echo $establishment->name; ?></h2>
				<p>Check the right section entitled "Beers Already Added" to make sure the beer you are adding has not already been added.</p>
<?php
	$this->load->view('beer/add/form.php');
}
?>                
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-3">
                <div class="side-info">
<?php
if ($action == 'no_sell' || $action == 'max_reviews') {                
?>
<?php
}
elseif ($action == 'show_form') {
?>
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Create Carefully</div>
                        <ul class="list-group">
                            <li class="list-group-item">Two Beer Dudes is about American craft beer.</li>
                            <li class="list-group-item">Please check that the beer doesn&#39;t exist already.  Duplicates create confussion.</li>
                        </ul>
                    </div>
<?php
	if (count($other_beers) > 0) {
?>	                    
 					<div class="panel panel-default">
                        <div class="green bold panel-heading">Beers Already Added</div>
                        <ul class="list-group">
<?php
		foreach ($other_beers as $ob) {
?>                        
                            <li class="list-group-item">
                            	<a href="<?php echo base_url(); ?>beer/review/<?php $ob->id; ?>/<?php echo $ob->slug_beer; ?>"><?php echo $ob->beerName; ?></a>
                            	-
                            	<a class="green" href="<?php echo base_url(); ?>beer/style/<?php echo $ob->styleID; ?>"><?php echo $ob->style; ?></a>
                            </li>
<?php
		}
?>
                        </ul>
                    </div>
<?php
	}
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
