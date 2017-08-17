<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Phone
{
	private $_ci;	

	public function __construct() {
		$this->_ci =& get_instance();
	}

	public function formatPhone($num, $return_empty = false) {
		if (!empty($num)) {
			return '(' . substr($num, 0, 3) . ') ' . substr($num, 3, 3) . '-' . substr($num, 6, 4);
		}

		if ($return_empty) {
			return '';
		}
		return 'N/A';
	}
}