<?php
switch ($type)
{
	case 'create':
		$text = 'Create';
		break;
	case 'reply':
		$text = 'Reply to';
		break;
	case 'forward':
		$text = 'Forwarded';
		break;
}
?>
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-xs-12">
				<h2 class="brown"><?php echo $text; ?> Malt Mail</h2>
				<div class="alert alert-success" role="alert">
					<p>Your message has been sent to <?php echo $to; ?></p>
					<p>You will be redirected in <span id="timer" class="label label-info"></span> seconds</p>
				</div>
			</div>
            
            <div class="col-md-3 col-xs-12">
    			<div class="side-info">
<?php $this->load->view('user/profile/right.php'); ?>
    			</div>
    		</div>
        </div>
	</div>
