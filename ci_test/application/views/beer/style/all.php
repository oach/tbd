
	<h2 class="brown">Beer Style</h2>
	<p>Beer styles help to define the beer that we are drinking.  Understanding beer styles will give one a better
	idea of the characteristics of a particular beer: visually, aroma, taste, mouthfeel, and overall.  These
	guidelines should be used when reviewing a beer and deciding if you like it or not.  There is always the
	first impression of a beer but if it is made with a clear understanding of style, the beer can be enjoyed for
	what the brewer intended.</p>

	<div class="row">
<?php
if (count($styles) > 0) {
	$origin = [];
	$style_type_list = [];
	
	foreach ($styles as $style) {
		if (!in_array($style->styleType, $style_type_list)) {
			if (count($style_type_list) > 0) {
				unset($origin);
				$origin = [];
			}
			if (count($style_type_list) == 1) {
?>
		</div>
<?php
			}
			if (count($style_type_list) < 2) {
?>	
		<div class="col-xs-12 col-sm-6 col-md-6">
<?php
			}
?>
	<h3 class="brown"><?php echo $style->styleType; ?>s</h3>
<?php
			$style_type_list[] = $style->styleType;
		}

		if (!in_array($style->origin, $origin)) {
			if (count($origin) > 0) {
?>
	<br>
<?php			
			}

			if (!empty($style->origin)) {
				$origin[] = $style->origin;
?>
	<span class="green bold"><?php echo $style->origin; ?></span><br>
<?php
			}
		}
?>
	<a href="<?php echo base_url(); ?>beer/style/<?php echo $style->id; ?>"><?php echo $style->style; ?></a><br>
<?php	
	}
?>
		</div>
	</div>
<?php	
}
?>
