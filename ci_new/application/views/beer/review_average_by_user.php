<?php
$average = $this->BeerModel->getNumBeersAndAverageByUserID($beer->userID);
if (count($average) > 0) {
?>
	<span><?php echo number_format($average->beersTasted); ?></span> beer<?php echo $average->beersTasted == 1 ? '' : 's';?> rated with a 
	<span class="label<?php echo $this->rating->getRatingsLabelClass($average->rating); ?>"><?php echo $average->rating; ?></span> average
<?php	
}
?>