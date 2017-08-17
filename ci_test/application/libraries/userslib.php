<?php
if(!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Userslib
{
    private $ci;

    public function __construct() {
            $this->ci =& get_instance();
    }
	
	public function showBuddyList($userInfo)
    {
		//echo '<pre>'; print_r($userInfo); echo '</pre>'; 
		$form = form_createMessage();
		//echo '<pre>'; print_r($form); echo '</pre>';
		
		$str = $form;
		// return the output
		return $str;
	}
}
?>
