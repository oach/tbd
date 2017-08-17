<?php
//public function showMessages($userInfo, $ajax = false, $actionButtons = true) {
$ab = '';
//		if($ajax === false) {
?>		
	<div id="maltedMail">
<?php					
			/*if($actionButtons === true) {
				// get the action buttons
				$button = $this->createMailActionButtons();
				if(!empty($button)) {
					// start the unordered list
					$ab = '<ul>';
					// get the buttons in the correct order
					foreach($button as $key => $value) {
						$ab .= '<li>' . $value . '</li>';
					}				
					// finish the unordered list
					$ab .= '</ul>';
	 			}
			}*/
//		}		
if (!$pms)
{
?>
		<div class="alert alert-warning" role="alert">
            <p>No private messages in your inbox.</p>
        </div>
<?php			
}
else
{
?>
		<div id="maltedMailInfo" class="maltedMailInfo"><?php echo count($pms) . ($cnt > 1 ? 'messages' : 'message'); ?></div>
<?php
	$i = 0;
	foreach ($pms as $item)
	{
		$bold = $item['timeRead'] == null ? ' class="bold"' : '';
		$class = ($i % 2) == 0 ? ' bg2' : '';
		$i++;
?>
		<div id="malted_<?php echo $item['id']; ?>" class="maltedMessage<?php echo $class; ?>">
			<div class="maltedImage"></div>
			<div class="maltedInfo">
				<div class="maltedLeft">
					<span<?php echo $bold; ?>><a href="<?php echo base_url(); ?>user/pms/showMessage/<?php echo $item['id']; ?>"><?php echo $item['subject']; ?></a></span>
					<br>
					<a style="text-decoration: none;" href="<?php echo base_url(); ?>user/profile/<?php echo $item['from_userID']; ?>" class="smallerText"><?php echo $item['username']; ?></a>
				</div>
				<div class="maltedRight"><?php echo $item['timesent']; ?></div>
			</div>
			<div class="maltedRemove"><a href="#" onclick="new Ajax.Request(\'<?php echo base_url(); ?>ajax/mailremove/<?php echo $item['id']; ?>\', {asynchronous: true, evalScripts: true, method: \'get\', onLoading: function() {showSpinner(\'maltedMail\');}, onComplete: function(response) {$(\'maltedMail\').update(response.responseText);}}); return false;">remove</a></div>
			<?php echo $ab; ?>
		</div>
<?php
	}
}
?>
	</div>
<?php		
		// finish the output
		/*if($ajax === false) {
			$str .= '</div>';
			// return the output
			return $str;
		} else {
			echo $str;
		}*/		
//	}
?>