<?php
	//$buttons = array();		
	//$buttons[2] = '<a href="' . base_url() . 'user/pms/create">New Malt Mail</a>';
		// check if there was a message id passed
		/*if($messageID > 0) {
			// create action for replying to message
			$buttons[0] = '<a href="' . base_url() . 'user/pms/reply/' . $messageID . '">Reply to Malt Mail</a>';
			// create action for forwarding message 
			$buttons[1] = '<a href="' . base_url() . '/user/pms/forward/' . $messageID . '">Forward Malt Mail</a>';
		}*/
		// sort the array
		//ksort($buttons);
		// return the action buttons
		//return $buttons;
	//}
?>
	<ul>
<?php
if (isset($messageID) && $messageID > 0)
{
?>
		<li><a href="<?php echo base_url(); ?>user/pms/reply/<?php echo $messageID; ?>">Reply to Malt Mail</a></li>
		<li><a href="<?php echo base_url(); ?>/user/pms/forward/<?php echo $messageID; ?>">Forward Malt Mail</a></li>
<?php
}
?>
		<li><a href="<?php echo base_url(); ?>user/pms/create">New Malt Mail</a></li>
	</ul>