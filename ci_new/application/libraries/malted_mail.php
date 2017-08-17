<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class MaltedMail
{
	private $_ci;

	public function __construct()
	{
		$this->_ci =& get_instance();
	}

	public function get_number_malted_mail_unread()
	{
		$this->_ci->load->model('UserModel', '', true);
        $user_info = $this->_ci->session->userdata('user_info');
		return $this->_ci->UserModel->num_new_messages(user_info['id']);
    }
}