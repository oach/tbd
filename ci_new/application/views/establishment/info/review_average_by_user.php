<?php
$average = $this->EstablishmentModel->getNumEstablishmentsAndAverageByUserID($rating->userID);
if (count($average) > 0)
{
?>
	<span><?php echo number_format($average->totalRatings); ?></span> establishment<?php echo $average->totalRatings == 1 ? '' : 's';?> rated with a 
	<span><?php echo $average->avergeRating; ?></span> average rating
<?php	
}
?>