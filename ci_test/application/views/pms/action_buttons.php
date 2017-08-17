<?php
if (isset($messageID) && $messageID > 0)
{
?>
		<a href="<?php echo base_url(); ?>pms/reply/<?php echo $messageID; ?>" class="btn btn-primary pull-right" role="botton">
			<span class="glyphicon glyphicon glyphicon-share" aria-hidden="true"></span> Reply
		</a>
<?php
}
?>
		