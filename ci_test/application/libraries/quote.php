<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Quote
{
	private $_ci;

	public function __construct() {
    	$this->_ci =& get_instance();
    }

	function getFooterQuote() {
		$this->_ci->load->model('QuoteModel', '', true);
		return $this->_ci->QuoteModel->getRandom();
	}
}