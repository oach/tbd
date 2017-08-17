Price: 
<?php
$mod = round(($rating->pricing / 2), 0);
$lingo = $this->pricing->getPriceLingo((integer) $mod);
$stars = 5 - $mod + 1;
for ($i = 0; $i < 5; $i++) {
	if ($i < $stars) {
?>
<img src="/images/stars/dollar.png" title="price for <?php echo $rating->name; ?> is <?php echo $lingo; ?>" alt="price for <?php echo $rating->name; ?> is <?php echo $lingo; ?>">
<?php
	}
	else {
?>
<img src="/images/stars/dollar_fade.png" title="price for <?php echo $rating->name; ?> is <?php echo $lingo; ?>" alt="price for <?php echo $rating->name; ?> is <?php echo $lingo; ?>">
<?php		
	}
}
?>
(<?php echo $lingo; ?>)
