<?php
switch($type) {
	case 'avatars':	
		echo 'Upload Image: New Avatar for ' . $user_info['username'];
		break;
	case 'beers':
		echo 'Upload Image: ' . $info->beerName . ' by ' . $info->name;
		break;
	case 'establishments':
		echo 'Upload Image: ' . $info->name;
		break;
}
?>