
	<div class="panel panel-default">
        <div class="green bold panel-heading">ABV Range</div>
        <ul class="list-group">
			<li class="list-group-item"><?php echo (!empty($style_info->abvrange) ? $style_info->abvrange . '%' : 'N/A'); ?></li>
		</ul>
	</div>

	<div class="panel panel-default">
        <div class="green bold panel-heading">IBU Range</div>
        <ul class="list-group">
			<li class="list-group-item"><?php echo (!empty($style_info->iburange) ? $style_info->iburange : 'N/A'); ?></li>
		</ul>
	</div>

	<div class="panel panel-default">
        <div class="green bold panel-heading">SRM Range</div>
        <ul class="list-group">
			<li class="list-group-item"><?php echo (!empty($style_info->srm) ? $style_info->srm : 'N/A'); ?></li>
		</ul>
	</div>
<?php
if (!empty($style_info->ogravity)) {
?>	
	<div class="panel panel-default">
        <div class="green bold panel-heading">Original Gravity Range</div>
        <ul class="list-group">
			<li class="list-group-item"><?php echo (!empty($style_info->ogravity) ? $style_info->ogravity : 'N/A'); ?></li>
		</ul>
	</div>
<?php
}

if (!empty($style_info->fgravity)) {
?>
	<div class="panel panel-default">
        <div class="green bold panel-heading">Final Gravity Range</div>
        <ul class="list-group">
			<li class="list-group-item"><?php echo (!empty($style_info->srm) ? $style_info->srm : 'N/A'); ?></li>
		</ul>
	</div>
<?php
}

if (count($similarBeers) > 0) {
?>				    
    <div class="panel panel-default">
        <div class="green bold panel-heading">Highest Rated In Style</div>
        <ul class="list-group">
<?php
	foreach ($similarBeers as $similar) {
?>
			<li class="list-group-item">
				<span class="label<?php echo $this->rating->getRatingsLabelClass($similar->avgRating); ?>"><?php echo $similar->avgRating; ?></span>
				<span><a href="<?php echo base_url(); ?>beer/review/<?php echo $similar->id . '/' . $similar->slug_beer; ?>"><?php echo $similar->beerName; ?></a></span>
				<span>by <a href="<?php echo base_url(); ?>brewery/info/<?php echo $similar->establishmentID . '/' . $similar->slug_establishment; ?>"><?php echo $similar->name; ?></a></span>
			</li>
<?php
	}
?>
        </ul>
    </div>
<?php
}
?>