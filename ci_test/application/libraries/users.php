<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Users
{
	private $ci;

	public function __construct()
	{
		$this->ci =& get_instance();
	}

	/**
	 * Checked if a user is logged in or not
	 * @return boolean
	 */
	public function checkLogin()
	{
		if (false !== $ci->session->userdata('userInfo')) {
			return true;
		}
		return false;
	}
}
?>