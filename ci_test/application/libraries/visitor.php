<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Visitor
{
	private $_ci;
	

	public function __construct()
	{
		$this->_ci =& get_instance();
	}

	/**
	 * Checked if a user is logged in or not
	 * @return boolean
	 */
	public function checkLogin()
	{
		if (false !== $this->_ci->session->userdata('userInfo')) {
			return true;
		}
		return false;
	}

	public function force_login()
	{
		//header('Location: ' . base_url() . $this->_ci->uri->uri_string());
		header('Location: ' . base_url() . 'user/login');
        exit;
	}

	public function adjustMailSubject($subject, $add)
	{
		if (empty($subject) || empty($add)) {
			return;
		}
		
		$possible = array(
			're:'
			, 'fwd:'
		);
		$subject = str_ireplace($possible, '', $subject);

		return strtoupper($add) . ': ' . trim($subject);
	}

	public function markupPreviousMessage($message)
	{
		return '[quote]' . 
		"\r\n" . $message->username . ' said on ' . $message->timesent . 
		"\r\n" . $message->message . 
		"\r\n" . '[/quote]' .
		"\r\n\r\n";
	}

	public function formatUserText($text, $limit = -1)
	{
        $text = trim($text);
        
        $array = array(
            '\[quote\]' => '<div class="pms_quote"><p>quote:</p>'
            , '\[\/quote]' => '</div>'
        );
        
        foreach ($array as $search => $replace) {
            $text = preg_replace('/' . $search . '/', $replace, $text, $limit);
        }
        return $text;
    }
}