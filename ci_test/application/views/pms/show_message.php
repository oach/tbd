	
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-sm-12 col-xs-12">
<?php
if (count($msg) > 0)
{
?>
				<h2 class="brown"><?php echo $msg[0]->subject; ?></h2>
<?php
	

	foreach ($msg as $item)
	{
		$avatar = $this->load->view('user/avatar.php', array('avatar_info' => $item, 'nubbin' => 0), true);

		/* <a href="<?php echo base_url(); ?>user/profile/<?php echo $item->from_userID; ?>"><?php echo $item->username; ?></a> */
		$location =	(!empty($item->city) && !empty($item->state)) ? '<br>' . $item->city . ', ' . $item->state : '' ;
?>			
				<div class="well">
					<div class="row">
						<div class="col-xs-12 col-sm-3 col-md-3">	
							
							<div class="panel panel-default">
								<div class="panel-body"><?php echo $avatar; ?>
								</div>
								<div class="panel-footer">
									<p>
										<a href="<?php echo base_url(); ?>user/profile/<?php echo $item->from_userID; ?>"><?php echo $item->username; ?></a><br>
										<?php echo $item->joindate; ?>
										<?php echo $location; ?>
									</p>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-9 col-md-9">
							<p><span class="bold"><?php echo $item->subject; ?></span> <?php echo $item->timesent; ?></p>
							<p><?php echo nl2br($this->visitor->formatUserText($item->message, 4)); ?></p>					
<?php 
$this->load->view('pms/action_buttons.php');
?>
						</div>
					</div>
				</div>
<?php
			
			// check if the message needs to be marked as read
			/*if($item['timeRead'] == null) {
				// mark the message as read
				$this->ci->UserModel->updateTimeRead($messageID, $userInfo['id']);
			}*/
		}
}
else
{
?>
				<h2 class="brown">Malted Mail Display Message</h2>
<?php
	$this->load->view('pms/bad_request.php', array('test' => 'this is test'));
}
/*}
else
{
	$this->load->view('user/pms/bad_request.php');
}*/
?>
			</div>
            
            <div class="col-xs-12 col-sm-12 col-md-3">
    			<div class="side-info">
<?php $this->load->view('user/profile/right.php'); ?>
    			</div>
    		</div>
        </div>
	</div>