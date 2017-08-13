<?php
$ab = '';
if (!$pms) {
?>
					<script>
					var display_error = true;
					var pms = 0;
					var s = 's';
					</script>
<?php			
} else {
	$count = count($pms);
?>
					<!--<div id="maltedMailInfo" class="maltedMailInfo">
						<p><span class="label label-default"><?php echo $count . ' message' . ($count > 1 ? 's' : ''); ?></span></p>
					</div>-->
<?php
	$i = 0;
	foreach ($pms as $item) {
		$bold = $item->timeRead == null ? ' class="bold"' : '';
		$class = ($i % 2) == 0 ? ' bg2' : '';
		$i++;
?>
					<div id="malted-<?php echo $item->id; ?>" class="malted-message<?php echo $class; ?>">
						<div class="maltedImage"></div>
						<div class="maltedInfo">
							<div class="maltedLeft">
								<span<?php echo $bold; ?>><a href="<?php echo base_url(); ?>pms/showMessage/<?php echo $item->id; ?>"><?php echo $item->subject; ?></a></span>
								<br>
								<a style="text-decoration: none;" href="<?php echo base_url(); ?>user/profile/<?php echo $item->from_userID; ?>" class="smallerText"><?php echo $item->username; ?></a>
							</div>
							<div class="malted-right"><?php echo $item->timesent; ?></div>
						</div>
						<div class="malted-remove"><a href="#" class="malted-remove-anchor" data-id="<?php echo $item->id; ?>">remove</a></div>
						<?php echo $ab; ?>
						<?php $this->load->view('pms/action_buttons.php'); ?>
					</div>
<?php
	}
?>
					<script>
					var display_error = false;
					var pms = <?php echo count($pms); ?>;
					var s = pms == 1 ? '' : 's';
					</script>
<?php	
}
?>