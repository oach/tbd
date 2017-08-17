<?php
//public function showMessageByID($messageID, $userInfo) {
		// get the information for this message
if ($messageID)
{	
	$msg = $this->UserModel->getPMByMessageID($messageID, $user_info['id']);

	if (count($msg) > 0)
	//if (1)
	{
		$this->load->view('user/pms/action_buttons.php');

		foreach ($msg as $item)
		{
			$avatar_info = $item;
			$this->load->view('user/avatar.php', $avatar_info);
				// get the avatar
				/*$avatar = 'nobody.gif';
				if($item['avatar'] && file_exists('./images/avatars/' . $item['avatarImage'])) {
					$avatar = $item['avatarImage'];
				} 
				$avatar = '<img src="' . base_url() . 'images/avatars/' . $avatar . '" title="' . $item['username'] . ' avatar picture" alt="' . $item['username'] . ' avatar picture" />';
				
				$location =	(!empty($item['city']) && !empty($item['state'])) ? $item['city'] . ', ' . $item['state'] : 'N/A' ;
				
				$str .= '			
			<div class="maltedLeft">	
				<div>Author</div>			
				<div>' . $avatar . '</div>
				<p><a href="' . base_url() . 'user/profile/' . $item['from_userID'] . '">' . $item['username'] . '</a></p>
				<p>Joined: ' . $item['joindate'] . '</p>
				<p>Location: ' . $location . '</p>
			</div>
			<div class="maltedRight">
				<div><span class="bold">' . $item['subject'] . '</span> ' . $item['timesent'] . '</div>
				<div>' . nl2br($this->formatUserText($item['message'], 4)) . '</div>
				' . $ab . '
			</div>
				';
				
				// check if the message needs to be marked as read
				if($item['timeRead'] == null) {
					// mark the message as read
					$this->ci->UserModel->updateTimeRead($messageID, $userInfo['id']);
				}*/
			}
	}
	else
	{
		$this->load->view('user/pms/bad_request.php', array('test' => 'this is test'));
	}
}
else
{
	$this->load->view('user/pms/bad_request.php');
}

/*		
		// start output
		$str = '<div id="maltedMail">';
		if($msg === false) {
			// no message matching passed in information
			$str = '<p>No message found matching requested information.</p>';
			
			// get the action buttons
			$button = $this->createMailActionButtons();
			if(!empty($button)) {
				$str .= '<ul>';
				// get the buttons in the correct order
				foreach($button as $key => $value) {
					$str .= '<li>' . $value . '</li>';				
				}	
				$str .= '</ul>';			
			}
		} else {
			// holder for action button textdomain
			$ab = '';
			// get the action buttons
			$button = $this->createMailActionButtons($messageID);
			if(!empty($button)) {
				$ab .= '<ul>';
				// get the buttons in the correct order
				foreach($button as $key => $value) {
					$ab .= '<li>' . $value . '</li>';				
				}	
				$ab .= '</ul>';			
			}			
			
			// iterate through the results
			foreach($msg as $item) {
				// get the avatar
				$avatar = 'nobody.gif';
				if($item['avatar'] && file_exists('./images/avatars/' . $item['avatarImage'])) {
					$avatar = $item['avatarImage'];
				} 
				$avatar = '<img src="' . base_url() . 'images/avatars/' . $avatar . '" title="' . $item['username'] . ' avatar picture" alt="' . $item['username'] . ' avatar picture" />';
				
				$location =	(!empty($item['city']) && !empty($item['state'])) ? $item['city'] . ', ' . $item['state'] : 'N/A' ;
				
				$str .= '			
			<div class="maltedLeft">	
				<div>Author</div>			
				<div>' . $avatar . '</div>
				<p><a href="' . base_url() . 'user/profile/' . $item['from_userID'] . '">' . $item['username'] . '</a></p>
				<p>Joined: ' . $item['joindate'] . '</p>
				<p>Location: ' . $location . '</p>
			</div>
			<div class="maltedRight">
				<div><span class="bold">' . $item['subject'] . '</span> ' . $item['timesent'] . '</div>
				<div>' . nl2br($this->formatUserText($item['message'], 4)) . '</div>
				' . $ab . '
			</div>
				';
				
				// check if the message needs to be marked as read
				if($item['timeRead'] == null) {
					// mark the message as read
					$this->ci->UserModel->updateTimeRead($messageID, $userInfo['id']);
				}
			}
		}		
		// finish the output
		$str .= '</div>';
		// return the output
		return $str;
	}*/