<?php
if ($user_info)
{
?>
	<a href="<?php echo base_url(); ?>page/updateInfo/beer/<?php echo $beer_info->id; ?>" id="update-beer-info">
	<!--<a href="#" id="update-beer-info">-->
		<img src="/images/update.gif" title="update beer information" alt="update beer information">
	</a>
<?php
}