<?php	
    if (!defined('BASEPATH'))
    {
    	exit('No direct script access allowed');
    }

    if( !function_exists('get_number_malted_mail_unread'))
    {
        function get_number_malted_mail_unread()
        {
        	$ci =& get_instance();
            if ($ci->_data['logged'])
            {                
        		$ci->load->model('UserModel', '', true);
                $user_info = $ci->session->userdata('user_info');
        
        		// get the number of unread private messages
        		return $ci->UserModel->num_new_messages($ci->_data['user_info']['id']);
            }
        }
    }

function updateActiveTime($id) {
	// get the code igniter instance
	$ci =& get_instance();
	$ci->UserModel->updateLastLogin($id);
}

function determineTimeSinceLastActive($secs) {
	$timeConv = array(
		'minutes' => 60
		, 'hours' => 3600
		, 'days' => 86400
		, 'months' => 2592000
		, 'years' => 31536000
	);
	
	$time = '';
	// check the years
	$years = floor(($secs / $timeConv['years']));
	if($years > 0) {
		//$time = $years > 1 ? 'more than ' . $years . ' years' : 'more than ' . $years . ' year';
            $time = $years > 1 ? $years . ' years' : $years . ' year';
	}
	
	// check the months
	if(empty($time)) {		
		$months = floor(($secs / $timeConv['months']));
		if($months > 0) {
			//$time = $months > 1 ? 'more than ' . $months . ' months' : 'more than ' . $months . ' month';
                        $time = $months > 1 ? $months . ' months' : $months . ' month';
		}
	}
	
	// check the days
	if(empty($time)) {
		$days = floor(($secs / $timeConv['days']));
		if($days > 0) {
			//$time = $days > 1 ? 'more than ' . $days . ' days' : 'more than ' . $days . ' day';
                    $time = $days > 1 ? $days . ' days' : $days . ' day';
		}
	}
	
	// check the hours
	if(empty($time)) {
		$hours = floor(($secs / $timeConv['hours']));
		if($hours > 0) {
			//$time = $hours > 1 ? 'more than ' . $hours . ' hours' : 'more than ' . $hours . ' hour';
                    $time = $hours > 1 ? $hours . ' hours' : $hours . ' hour';
		}
	}
	
	// check the minutes
	if(empty($time)) {
		$minutes = floor(($secs / $timeConv['minutes']));
		if($minutes > 0) {
			//$time = $minutes > 1 ? 'more than ' . $minutes . ' minutes' : 'more than ' . $minutes . ' minute';
                    $time = $minutes > 1 ? $minutes . ' minutes' : $minutes . ' minute';
		}
	}
	
	// check for seconds
	if(empty($time)) {
		//$time = $secs > 1 ? 'more than ' . $secs . ' seconds' : 'more than ' . $secs . ' second';
            $time = $secs > 1 ? $secs . ' seconds' : $secs . ' second';
	}
	
	return $time . ' ago';
}
?>